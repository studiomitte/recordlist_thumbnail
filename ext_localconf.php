<?php


switch ((new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion()) {
    case 13:
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\RecordList\DatabaseRecordList::class] = [
            'className' => \StudioMitte\RecordlistThumbnail\Xclass\V13\XclassedDatabaseRecordList::class,
        ];
        break;
    case 12:
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('wv_deepltranslate')) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\RecordList\DatabaseRecordList::class] = [
                'className' => \StudioMitte\RecordlistThumbnail\Xclass\V12\XclassedDatabaseRecordListWithWvDeeplTranslate::class,
            ];
        } else {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\RecordList\DatabaseRecordList::class] = [
                'className' => \StudioMitte\RecordlistThumbnail\Xclass\V12\XclassedDatabaseRecordList::class,
            ];
        }
        break;
    case 11:
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