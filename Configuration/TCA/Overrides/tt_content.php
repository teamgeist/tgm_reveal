<?php

TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('TgM.tgm_reveal', 'Reveal', 'Reveal.js');

$flexform = strtolower(TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('tgm_reveal')).'_reveal';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$flexform] = 'layout,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$flexform] = 'pi_flexform';
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($flexform, 'FILE:EXT:tgm_reveal/Resources/Private/Flexform/TgM_Reveal_Flexform.xml');
