<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Bring back thumbnails in recordlist',
    'description' => 'With TYPO3 11.5 the thumbnails have been removed from the recordlist. This extension brings those back',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => 'gr@studiomitte.com',
    'state' => 'alpha',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'wv_deepltranslate' => '2.3.1-2.99.99'
        ],
    ],
];
