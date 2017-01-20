<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if(!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/**
 * Adds an extension flexform
 */
$flexform = strtolower(GeneralUtility::underscoredToUpperCamelCase($_EXTKEY)) . '_reveal';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$flexform] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$flexform] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue($flexform, 'FILE:EXT:' . $_EXTKEY . '/Resources/Private/Flexform/TgM_Reveal_Flexform.xml');

/**
 * Registeres a new plugin
 */
ExtensionUtility::registerPlugin('TgM.' . $_EXTKEY, 'Reveal', 'TgM-Reveal.js');
ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'TgM reveal');
ExtensionManagementUtility::allowTableOnStandardPages('tx_tgmreveal_domain_model_reveal');

/**
 * "TgM reveal.js-Additional" palettes
 */
$TCA['pages']['palettes']['tca_fields_additional_extra1']['showitem'] = 'tx_tgm_reveal_notes';
$TCA['pages']['palettes']['tca_fields_additional_extra2']['showitem'] = 'tx_tgm_reveal_transition,tx_tgm_reveal_state';
$TCA['pages']['palettes']['tca_fields_additional_extra3']['showitem'] = 'tx_tgm_reveal_markdown,tx_tgm_reveal_trim';
$TCA['pages']['palettes']['tca_fields_additional_extra4']['showitem'] = 'tx_tgm_reveal_additional_attributes';
ExtensionManagementUtility::addToAllTCAtypes('pages', '--div--;TgM reveal.js-Additional;,' .
	'--palette--;Configure additional settings...;tca_fields_additional_extra1,' .
	'--palette--;;tca_fields_additional_extra2,' .
	'--palette--;;tca_fields_additional_extra3,' .
	'--palette--;;tca_fields_additional_extra4,'
	, '', 'after:categories');

/**
 * "TgM reveal.js" palettes
 */
$TCA['pages']['palettes']['tca_fields_bg_type']['showitem'] = 'tx_tgm_reveal_bg_type,tx_tgm_reveal_bg_image_selectBy,tx_tgm_reveal_bg_video_selectBy';
$TCA['pages']['palettes']['tca_fields_bg_value']['showitem'] = 'tx_tgm_reveal_bg_color,tx_tgm_reveal_bg_image_selectBy_fal,tx_tgm_reveal_bg_image_selectBy_link,tx_tgm_reveal_bg_video_selectBy_fal,tx_tgm_reveal_bg_video_selectBy_link,tx_tgm_reveal_bg_iframe';
$TCA['pages']['palettes']['tca_fields_bg_extra1']['showitem'] = 'tx_tgm_reveal_bg_image_size,tx_tgm_reveal_bg_video_loop,tx_tgm_reveal_bg_video_muted';
$TCA['pages']['palettes']['tca_fields_bg_extra2']['showitem'] = 'tx_tgm_reveal_bg_image_position';
$TCA['pages']['palettes']['tca_fields_bg_extra3']['showitem'] = 'tx_tgm_reveal_bg_image_repeat';
ExtensionManagementUtility::addToAllTCAtypes('pages', '--div--;TgM reveal.js;,' .
	'--palette--;Choose your background type and configure it\'s settings...;tca_fields_bg_type,' .
	'--palette--;;tca_fields_bg_value,' .
	'--palette--;;tca_fields_bg_extra1,' .
	'--palette--;;tca_fields_bg_extra2,' .
	'--palette--;;tca_fields_bg_extra3,'
	, '', 'after:categories');