<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/*
 * Registeres a new plugin
 */
TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('TgM.'.$_EXTKEY, 'Reveal', 'TgM-Reveal.js');
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'TgM reveal');
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tgmreveal_domain_model_reveal');
