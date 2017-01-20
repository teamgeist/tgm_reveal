<?php

/**
 * - reveal.js-Version: 3.4.0 (10.01.2017)
 * - modified file "tgm_reveal/Resources/Public/JavaScript/plugin/notes/notes.html and implemented a "white-space"-css fix (does not work with source-minifier)
 * - modified some themes (changed font-path)
 */
$EM_CONF[$_EXTKEY] = [
	'title' => 'TgM-reveal.js',
	'description' => 'A TYPO3-Extension for the jQuery-plugin "reveal.js".',
	'category' => 'plugin',
	'author' => 'EG',
	'author_email' => 'eg@teamgeist-medien.de',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => 'fileadmin/ext/tgm_reveal',
	'clearCacheOnLoad' => 0,
	'version' => '1.0.0',
	'constraints' => [
		'depends' => ['typo3' => '7.6.0-7.6.99'],
		'conflicts' => [],
		'suggests' => []
	]
];