<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * Forces a page/frame reload if the background type or the way how to include the background is changed.
 * Includes condition which checks presence or length of the TCA which already exists to prevent compatibility problems.
 */
$requestUpdateTCA = &$GLOBALS['TCA']['pages']['ctrl']['requestUpdate'];
$requestUpdateTCA .= (is_null($requestUpdateTCA) || (0 == strlen(trim($requestUpdateTCA))) ? '' : ',')
    .'tx_tgm_reveal_bg_type,tx_tgm_reveal_bg_image_selectBy,tx_tgm_reveal_bg_video_selectBy';

ExtensionManagementUtility::addTCAcolumns('pages', [
    'tx_tgm_reveal_notes' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_notes',
        'config' => [
            'type' => 'text',
            'placeholder' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_transition_notes',
        ],
    ],
    'tx_tgm_reveal_transition' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_transition',
        'config' => [
            'type' => 'input',
            'placeholder' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_transition_placeholder',
        ],
    ],
    'tx_tgm_reveal_state' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_state',
        'config' => ['type' => 'input'],
    ],
    'tx_tgm_reveal_markdown' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_markdown',
        'config' => [
            'type' => 'select',
            'default' => 'false',
            'items' => [
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_yes', 'true'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_no', 'false'],
            ],
        ],
    ],
    'tx_tgm_reveal_trim' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_trim',
        'config' => [
            'type' => 'select',
            'default' => 'false',
            'items' => [
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_yes', 'true'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_no', 'false'],
            ],
        ],
    ],
    'tx_tgm_reveal_additional_attributes' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_additional_attributes',
        'config' => ['type' => 'input'],
    ],
]);
ExtensionManagementUtility::addTCAcolumns('pages', [
    'tx_tgm_reveal_bg_type' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_type',
        'config' => [
            'type' => 'select',
            'items' => [
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_type_none', 'none'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_type_color', 'color'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_type_image', 'image'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_type_video', 'video'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_type_iframe', 'iframe'],
            ],
        ],
    ],
    'tx_tgm_reveal_bg_color' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_color',
        'displayCond' => 'FIELD:tx_tgm_reveal_bg_type:=:color',
        'config' => [
            'type' => 'input',
            'eval' => 'required,trim,upper',
            'default' => '#ABC',
            'wizards' => [
                'colorChoice' => [
                    'type' => 'colorbox',
                    'title' => 'Title',
                    'JSopenParams' => 'height=380,width=580,status=0,menubar=0,scrollbars=1',
                    'module' => ['name' => 'wizard_colorpicker'],
                ],
            ],
        ],
    ],
    'tx_tgm_reveal_bg_image_selectBy' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy',
        'displayCond' => 'FIELD:tx_tgm_reveal_bg_type:=:image',
        'config' => [
            'type' => 'select',
            'items' => [
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy_fal', 'fal'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy_link', 'link'],
            ],
        ],
    ],
    'tx_tgm_reveal_bg_image_selectBy_fal' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy_fal_label',
        'displayCond' => [
            'AND' => [
                'FIELD:tx_tgm_reveal_bg_type:=:image',
                'OR' => [
                    'FIELD:tx_tgm_reveal_bg_image_selectBy:=:',
                    'FIELD:tx_tgm_reveal_bg_image_selectBy:=:fal',
                ],
            ],
        ],
        'config' => ExtensionManagementUtility::getFileFieldTCAConfig('tx_tgm_reveal_bg_image_selectBy_fal', ['maxitems' => 1], 'gif,jpg,jpeg,png'),
    ],
    'tx_tgm_reveal_bg_image_selectBy_link' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy_link_label',
        'displayCond' => [
            'AND' => [
                'FIELD:tx_tgm_reveal_bg_type:=:image',
                'FIELD:tx_tgm_reveal_bg_image_selectBy:=:link',
            ],
        ],
        'config' => [
            'type' => 'input',
            'eval' => 'required,trim,nospace',
            'max' => 255,
        ],
    ],
    'tx_tgm_reveal_bg_image_size' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_size',
        'displayCond' => 'FIELD:tx_tgm_reveal_bg_type:=:image',
        'config' => [
            'type' => 'input',
            'placeholder' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_size_placeholder',
        ],
    ],
    'tx_tgm_reveal_bg_image_position' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_position',
        'displayCond' => 'FIELD:tx_tgm_reveal_bg_type:=:image',
        'config' => [
            'type' => 'input',
            'placeholder' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_position_placeholder',
        ],
    ],
    'tx_tgm_reveal_bg_image_repeat' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_repeat',
        'displayCond' => 'FIELD:tx_tgm_reveal_bg_type:=:image',
        'config' => [
            'type' => 'select',
            'default' => 'no-repeat',
            'items' => [
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_repeat_noRepeat', 'no-repeat'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_repeat_repeatX', 'repeat-x'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_repeat_repeatY', 'repeat-y'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_repeat_repeat', 'repeat'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_repeat_initial', 'initial'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_image_repeat_inherit', 'inherit'],
            ],
        ],
    ],
    'tx_tgm_reveal_bg_video_selectBy' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy',
        'displayCond' => 'FIELD:tx_tgm_reveal_bg_type:=:video',
        'config' => [
            'type' => 'select',
            'items' => [
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy_fal', 'fal'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy_link', 'link'],
            ],
        ],
    ],
    'tx_tgm_reveal_bg_video_selectBy_fal' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy_fal_label',
        'displayCond' => [
            'AND' => [
                'FIELD:tx_tgm_reveal_bg_type:=:video',
                'OR' => [
                    'FIELD:tx_tgm_reveal_bg_video_selectBy:=:',
                    'FIELD:tx_tgm_reveal_bg_video_selectBy:=:fal',
                ],
            ],
        ],
        'config' => ExtensionManagementUtility::getFileFieldTCAConfig('tx_tgm_reveal_bg_video_selectBy_fal', ['maxitems' => 1], 'mp4'),
    ],
    'tx_tgm_reveal_bg_video_selectBy_link' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_selectBy_link_label',
        'displayCond' => [
            'AND' => [
                'FIELD:tx_tgm_reveal_bg_type:=:video',
                'FIELD:tx_tgm_reveal_bg_video_selectBy:=:link',
            ],
        ],
        'config' => [
            'type' => 'input',
            'eval' => 'required,trim,nospace',
            'max' => 255,
        ],
    ],
    'tx_tgm_reveal_bg_video_loop' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_video_loop',
        'displayCond' => 'FIELD:tx_tgm_reveal_bg_type:=:video',
        'config' => [
            'type' => 'select',
            'default' => 'false',
            'items' => [
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_yes', 'true'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_no', 'false'],
            ],
        ],
    ],
    'tx_tgm_reveal_bg_video_muted' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_video_muted',
        'displayCond' => 'FIELD:tx_tgm_reveal_bg_type:=:video',
        'config' => [
            'type' => 'select',
            'default' => 'false',
            'items' => [
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_yes', 'true'],
                ['LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_no', 'false'],
            ],
        ],
    ],
    'tx_tgm_reveal_bg_iframe' => [
        'label' => 'LLL:EXT:tgm_reveal/Resources/Private/Language/locallang.xlf:tx_tgm_reveal_bg_iframe',
        'displayCond' => 'FIELD:tx_tgm_reveal_bg_type:=:iframe',
        'config' => [
            'type' => 'input',
            'eval' => 'required',
            'size' => 48,
        ],
    ],
]);

/*
 * "TgM reveal.js-Additional" palettes
 */
$GLOBALS['TCA']['pages']['palettes']['tca_fields_additional_extra1']['showitem'] = 'tx_tgm_reveal_notes';
$GLOBALS['TCA']['pages']['palettes']['tca_fields_additional_extra2']['showitem'] = 'tx_tgm_reveal_transition,tx_tgm_reveal_state';
$GLOBALS['TCA']['pages']['palettes']['tca_fields_additional_extra3']['showitem'] = 'tx_tgm_reveal_markdown,tx_tgm_reveal_trim';
$GLOBALS['TCA']['pages']['palettes']['tca_fields_additional_extra4']['showitem'] = 'tx_tgm_reveal_additional_attributes';
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--div--;TgM reveal.js-Additional;,'.
    '--palette--;Configure additional settings...;tca_fields_additional_extra1,'.
    '--palette--;;tca_fields_additional_extra2,'.
    '--palette--;;tca_fields_additional_extra3,'.
    '--palette--;;tca_fields_additional_extra4,', '', 'after:categories');

/*
 * "TgM reveal.js" palettes
 */
$GLOBALS['TCA']['pages']['palettes']['tca_fields_bg_type']['showitem'] = 'tx_tgm_reveal_bg_type,tx_tgm_reveal_bg_image_selectBy,tx_tgm_reveal_bg_video_selectBy';
$GLOBALS['TCA']['pages']['palettes']['tca_fields_bg_value']['showitem'] = 'tx_tgm_reveal_bg_color,tx_tgm_reveal_bg_image_selectBy_fal,tx_tgm_reveal_bg_image_selectBy_link,tx_tgm_reveal_bg_video_selectBy_fal,tx_tgm_reveal_bg_video_selectBy_link,tx_tgm_reveal_bg_iframe';
$GLOBALS['TCA']['pages']['palettes']['tca_fields_bg_extra1']['showitem'] = 'tx_tgm_reveal_bg_image_size,tx_tgm_reveal_bg_video_loop,tx_tgm_reveal_bg_video_muted';
$GLOBALS['TCA']['pages']['palettes']['tca_fields_bg_extra2']['showitem'] = 'tx_tgm_reveal_bg_image_position';
$GLOBALS['TCA']['pages']['palettes']['tca_fields_bg_extra3']['showitem'] = 'tx_tgm_reveal_bg_image_repeat';
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--div--;TgM reveal.js;,'.
    '--palette--;Choose your background type and configure it\'s settings...;tca_fields_bg_type,'.
    '--palette--;;tca_fields_bg_value,'.
    '--palette--;;tca_fields_bg_extra1,'.
    '--palette--;;tca_fields_bg_extra2,'.
    '--palette--;;tca_fields_bg_extra3,', '', 'after:categories');
