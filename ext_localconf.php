<?php


$isTypo312Up = (new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion() >= 12;
if ($isTypo312Up) {
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('wv_deepltranslate')) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\RecordList\DatabaseRecordList::class] = [
            'className' => \StudioMitte\RecordlistThumbnail\Xclass\V12\XclassedDatabaseRecordListWithWvDeeplTranslate::class,
        ];
    } else {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\RecordList\DatabaseRecordList::class] = [
            'className' => \StudioMitte\RecordlistThumbnail\Xclass\V12\XclassedDatabaseRecordList::class,
        ];
    }
} else {
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('wv_deepltranslate')) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList::class] = [
            'className' => \StudioMitte\RecordlistThumbnail\Xclass\XclassedDatabaseRecordListWithWvDeeplTranslate::class,
        ];
    } else {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList::class] = [
            'className' => \StudioMitte\RecordlistThumbnail\Xclass\XclassedDatabaseRecordList::class,
        ];
    }

}
