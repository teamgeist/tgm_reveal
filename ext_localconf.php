<?php
use TgM\TgmReveal\Controller\RevealController;
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if(!defined('TYPO3_MODE')) {
	die('Access denied.');
}

ExtensionUtility::configurePlugin('TgM.' . RevealController::EXT_KEY, 'Reveal', ['Reveal' => 'list, show'], ['Reveal' => '']);

/**
 * Adds the page ts config for the "TgM-Reveal"-plugin
 */
ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . RevealController::EXT_KEY . '/Configuration/TSconfig/ContentElementWizard.txt">');

/**
 * Registeres backend icons (required for wizards of content elements)
 */
if(TYPO3_MODE === 'BE') {
	$icons = ['ext-tgm_reveal-wizard-icon' => 'plugin_wizard.png'];

	/** @var IconRegistry $iconRegistry */
	$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
	foreach ($icons as $identifier => $path) {
		$iconRegistry->registerIcon($identifier, BitmapIconProvider::class, ['source' => 'EXT:' . RevealController::EXT_KEY . '/Resources/Public/Icons/' . $path]);
	}
}

/**
 * Adds a hook for the preview of the content element (tgm_reveal-plugin) in the backend
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['list'] = TgM\TgmReveal\Hooks\BackendViewDrawItemHook::class;