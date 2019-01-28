<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "tgm_reveal".
 ***************************************************************/

$EM_CONF['tgm_reveal'] = [
    'title' => 'reveal.js for TYPO3',
    'description' => 'A TYPO3-Extension for the jQuery-plugin "reveal.js".',
    'category' => 'plugin',
    'author' => 'EG',
    'author_email' => 'eg@teamgeist-medien.de',
    'state' => 'beta',
    'uploadfolder' => false,
    'createDirs' => 'fileadmin/ext/tgm_reveal',
    'clearCacheOnLoad' => 0,
    'version' => '1.1.0',
    'constraints' => [
        'depends' => [
            'php' => '7.0.0-7.2.99',
            'typo3' => '7.6.0-8.7.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'clearcacheonload' => false,
    'author_company' => null,
];
