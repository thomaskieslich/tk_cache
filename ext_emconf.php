<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'BE Cache Handling',
    'description' => 'BE Cache Handling, Monitoring',
    'category' => 'backend',
    'state' => 'stable',
    'author' => 'Thomas Kieslich',
    'author_email' => 'post@thomaskieslich.de',
    'version' => '9.5.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [
        ],
    ],
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1
];
