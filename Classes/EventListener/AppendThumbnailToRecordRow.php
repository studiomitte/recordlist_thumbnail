<?php

declare(strict_types=1);

namespace StudioMitte\RecordlistThumbnail\EventListener;

use StudioMitte\RecordlistThumbnail\Configuration;
use TYPO3\CMS\Backend\RecordList\Event\AfterRecordListRowPreparedEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Database\RelationHandler;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Schema\Capability\TcaSchemaCapability;
use TYPO3\CMS\Core\Schema\Field\FileFieldType;
use TYPO3\CMS\Core\Schema\TcaSchemaFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

#[AsEventListener('studiomitte/recordlist-thumbnail/append-thumbnail-to-record-row')]
final class AppendThumbnailToRecordRow
{
    private const THUMBNAIL_SIZE = 64;

    public function __construct(
        private readonly Configuration $configuration,
        private readonly TcaSchemaFactory $tcaSchemaFactory,
        private readonly IconFactory $iconFactory,
        private readonly ResourceFactory $resourceFactory,
    ) {}

    public function __invoke(AfterRecordListRowPreparedEvent $event): void
    {
        $table = $event->getTable();
        $thumbnailField = $this->configuration->getField($table);
        if ($thumbnailField === '') {
            return;
        }
        if (!$this->tcaSchemaFactory->has($table)) {
            return;
        }
        $schema = $this->tcaSchemaFactory->get($table);
        if (!$schema->hasField($thumbnailField)) {
            return;
        }
        $field = $schema->getField($thumbnailField);
        if (!$field instanceof FileFieldType) {
            return;
        }
        if (!$schema->hasCapability(TcaSchemaCapability::Label)) {
            return;
        }
        $titleColumn = $schema->getCapability(TcaSchemaCapability::Label)->getPrimaryFieldName();
        $data = $event->getData();
        if (!isset($data[$titleColumn])) {
            return;
        }
        $rawRecord = $event->getRecord()->getRawRecord();
        if ($rawRecord === null) {
            return;
        }
        $thumbnailHtml = $this->renderThumbnails($table, $field, $rawRecord->toArray());
        if ($thumbnailHtml === '') {
            return;
        }
        $augmented = '<br />' . $thumbnailHtml;
        $data[$titleColumn] .= $augmented;
        if (isset($data['__label'])) {
            $data['__label'] .= $augmented;
        }
        $event->setData($data);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderThumbnails(string $table, FileFieldType $field, array $row): string
    {
        $fileReferences = $this->resolveFileReferences($table, $field, $row);
        if ($fileReferences === []) {
            return '';
        }
        $elements = [];
        foreach ($fileReferences as $fileReference) {
            $elements[] = $this->renderThumbnail($fileReference);
        }
        return '<div class="preview-thumbnails" style="--preview-thumbnails-size: ' . self::THUMBNAIL_SIZE . 'px">'
            . implode('', $elements)
            . '</div>';
    }

    private function renderThumbnail(FileReference $fileReference): string
    {
        $originalFile = $fileReference->getOriginalFile();
        if ($originalFile->isMissing()) {
            $icon = $this->iconFactory
                ->getIcon('mimetypes-other-other', IconSize::MEDIUM, 'overlay-missing')
                ->setTitle($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:warning.file_missing') . ' ' . $originalFile->getName())
                ->render();
            return '<div class="preview-thumbnails-element">' . $icon . '</div>';
        }
        $cropString = (string)($fileReference->getProperty('crop') ?? '');
        $cropArea = CropVariantCollection::create($cropString)->getCropArea('default');
        $processingConfiguration = [
            'width' => self::THUMBNAIL_SIZE,
            'height' => self::THUMBNAIL_SIZE,
        ];
        $processingContext = ProcessedFile::CONTEXT_IMAGEPREVIEW;
        if (!$cropArea->isEmpty()) {
            $processingConfiguration['crop'] = $cropArea->makeAbsoluteBasedOnFile($originalFile);
            $processingContext = ProcessedFile::CONTEXT_IMAGECROPSCALEMASK;
        }
        $processedFile = $originalFile->process($processingContext, $processingConfiguration);
        $publicUrl = $processedFile->getPublicUrl();
        if ($publicUrl === null) {
            return '';
        }
        $alternative = $fileReference->getAlternative();
        $alt = $alternative !== '' ? $alternative : $fileReference->getName();
        return '<div class="preview-thumbnails-element">'
            . '<div class="preview-thumbnails-element-image">'
            . '<a href="#" data-dispatch-action="TYPO3.InfoWindow.showItem" data-dispatch-args-list="_FILE,' . (int)$originalFile->getUid() . '">'
            . '<img src="' . htmlspecialchars($publicUrl) . '"'
            . ' width="' . self::THUMBNAIL_SIZE . '" height="' . self::THUMBNAIL_SIZE . '"'
            . ' alt="' . htmlspecialchars($alt) . '"'
            . ' loading="lazy"/>'
            . '</a>'
            . '</div>'
            . '</div>';
    }

    /**
     * Inlined replacement for the deprecated BackendUtility::resolveFileReferences().
     *
     * @param array<string, mixed> $row
     * @return list<FileReference>
     */
    private function resolveFileReferences(string $table, FileFieldType $field, array $row): array
    {
        $relationHandler = GeneralUtility::makeInstance(RelationHandler::class);
        $relationHandler->initializeForField(
            $table,
            $field->getConfiguration(),
            $row,
            $row[$field->getName()] ?? '',
        );
        $relationHandler->processDeletePlaceholder();
        $referenceUids = $relationHandler->tableArray[$field->getConfiguration()['foreign_table'] ?? 'sys_file_reference'] ?? [];

        $fileReferences = [];
        foreach ($referenceUids as $referenceUid) {
            try {
                $fileReferences[] = $this->resourceFactory->getFileReferenceObject((int)$referenceUid);
            } catch (FileDoesNotExistException | \InvalidArgumentException) {
                // Reference cannot be resolved — skip it; matches core's behaviour
            }
        }
        return $fileReferences;
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
