<?php

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('wv_deepltranslate')) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList::class] = [
        'className' => \StudioMitte\RecordlistThumbnail\Xclass\XclassedDatabaseRecordListWithWvDeeplTranslate::class,
    ];
} else {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList::class] = [
        'className' => \StudioMitte\RecordlistThumbnail\Xclass\XclassedDatabaseRecordList::class,
    ];
}
