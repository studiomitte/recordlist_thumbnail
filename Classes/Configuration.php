<?php
declare(strict_types=1);

namespace StudioMitte\RecordlistThumbnail;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Configuration
{

    /**
     * @return string[]
     */
    public function getFields(): array
    {
        return $this->getSettings();
    }

    public function getField(string $tableName): array
    {
        $settings = $this->getSettings();
        return $settings[$tableName] ?? '';
    }

    /**
     * @return string[]
     */
    protected function getSettings(): array
    {
        try {
            $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('recordlist_thumbnail', 'list');
            $settings = [];
            foreach (GeneralUtility::trimExplode(',', $configuration, true) as $item) {
                $split = GeneralUtility::trimExplode('=', $item, true);
                if (count($split) !== 2) {
                    continue;
                }
                $settings[$split[0]][] = $split[1];
            }
            return $settings;
        } catch (ExtensionConfigurationExtensionNotConfiguredException $e) {
            // do nothing
        } catch (ExtensionConfigurationPathDoesNotExistException $e) {
            // do nothing
        }

        return [];
    }
}
