<?php

defined('TYPO3') or die();

$majorVersion = (new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion();

if ($majorVersion === 13) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\RecordList\DatabaseRecordList::class] = [
        'className' => \StudioMitte\RecordlistThumbnail\Xclass\V13\XclassedDatabaseRecordList::class,
    ];
} elseif ($majorVersion === 12) {
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('wv_deepltranslate')) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\RecordList\DatabaseRecordList::class] = [
            'className' => \StudioMitte\RecordlistThumbnail\Xclass\V12\XclassedDatabaseRecordListWithWvDeeplTranslate::class,
        ];
    } else {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\RecordList\DatabaseRecordList::class] = [
            'className' => \StudioMitte\RecordlistThumbnail\Xclass\V12\XclassedDatabaseRecordList::class,
        ];
    }
}
// On TYPO3 v14+ the thumbnail is appended via AfterRecordListRowPreparedEvent;
// see Classes/EventListener/AppendThumbnailToRecordRow.php (registered via Configuration/Services.yaml).
