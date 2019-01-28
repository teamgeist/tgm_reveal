<?php

namespace TgM\TgmReveal\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 EG <eg@teamgeist-medien.de>, Teamgeist Medien
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * RevealController.
 */
class RevealController extends ActionController
{
    /**
     * The extension key.
     */
    const EXT_KEY = 'tgm_reveal';

    /**
     * revealRepository.
     *
     * @var \TgM\TgmReveal\Domain\Repository\RevealRepository
     * @inject
     */
    protected $revealRepository = null;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @var array Contains every data types from reveal.js-initialisation-options.
     */
    protected $flexformDataTypes = [
        'boolean' => [
            'controls', 'progress', 'slideNumber', 'history', 'keyboard', 'overview', 'center', 'touch', 'loop', 'rtl', 'shuffle', 'fragments', 'fragments', 'embedded',
            'help', 'showNotes', 'autoSlideStoppable', 'mouseWheel', 'hideAddressbar', 'previewLinks',
        ],
        'string' => ['autoSlideMethod', 'transition', 'transitionSpeed', 'backgroundTransition', 'parallaxBackgroundImage', 'parallaxBackgroundSize'],
        'integer' => ['autoSlide', 'viewDistance', 'parallaxBackgroundHorizontal', 'parallaxBackgroundVertical'],
    ];

    /**
     * @var array Contains all flexform settings which are not available for the reveal.js-initialisation-options
     */
    protected $nonJSInitOptions = ['theme', 'userCSS', 'userJS', 'userPlugins', 'enableFancybox', 'disableBrowserZooming'];

    /**
     * action list.
     */
    public function listAction()
    {
        $reveals = $this->revealRepository->findAll();
        $this->view->assign('reveals', $reveals);
        $this->includeCSSAndJSFiles();
    }

    /**
     * Includes all required CSS and JavaScript files.
     */
    private function includeCSSAndJSFiles()
    {
        /**
         * The directory path where extension-required css- and js-files are stored.
         */
        $extFilePath = ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()).'Resources/Public/';

        /**
         * CSS files to include.
         */
        $cssFileArray = [
            $extFilePath.'CSS/reveal.css',
            $extFilePath.'CSS/theme/'.$this->settings['theme'].'.css',
            $extFilePath.'CSS/tgm_reveal.css',
            '/'.$this->saveUserFileAndReturnPath('userCSS.css', 'userCSS'),
        ];

        /**
         * JavaScript files to include.
         */
        $jsFileArray = [
            $extFilePath.'JavaScript/lib/head.min.js',
            $extFilePath.'JavaScript/lib/reveal.js',
            $extFilePath.'JavaScript/tgm_reveal.js',
            '/'.$this->saveUserFileAndReturnPath('userJS.js', 'userJS'),
        ];

        /*
         * Includes other files like Fancybox if required
         */
        $this->includeOtherFilesIfRequired($extFilePath.'JavaScript/', $cssFileArray, $jsFileArray);

        /*
         * Adds required <link>-tags at the end of the <header>-tag
         */
        $GLOBALS['TSFE']->additionalHeaderData[self::EXT_KEY] = $this->buildSourceTag($cssFileArray, '<link rel="stylesheet" href="%s" media="all"> ');

        /**
         * Adds required <source>-tags (and "reveal.js" initialisation script) at the end of the <body>-tag.
         */
        $jsFiles = $this->buildSourceTag($jsFileArray, '<script type="text/javascript" src="%s"></script> ');
        $GLOBALS['TSFE']->additionalFooterData[self::EXT_KEY] = $jsFiles.$this->buildScript();
    }

    /**
     * Includes other files if they're needed (like Fancybox or diabling browser zooming).
     *
     * @param string $extFilePath  Path to the file directory
     * @param array  $cssFileArray Path to the CSS file
     * @param array  $jsFileArray  Path to the JavaScript file
     */
    private function includeOtherFilesIfRequired(string $extFilePath, array &$cssFileArray, array &$jsFileArray)
    {
        if ($this->settings['enableFancybox']) {
            $cssFileArray[] = $extFilePath.'plugin/fancybox/jquery.fancybox.css';
            $jsFileArray[] = $extFilePath.'plugin/fancybox/jquery.fancybox.pack.js';
        }
        if ($this->settings['disableBrowserZooming']) {
            $jsFileArray[] = $extFilePath.'disableBrowserZooming.js';
        }
    }

    /**
     * action show.
     *
     * @param \TgM\TgmReveal\Domain\Model\Reveal $reveal
     */
    public function showAction(\TgM\TgmReveal\Domain\Model\Reveal $reveal)
    {
        $this->view->assign('reveal', $reveal);
    }

    /**
     * Builds a html-tag.
     *
     * @param array  $data    The array which contains every tag to include
     * @param string $tagData Predefined html-tag
     *
     * @return string Every data with their tags as a single string
     */
    private function buildSourceTag(array $data, string $tagData): string
    {
        $tag = '';
        foreach ($data as $filePath) {
            $tag .= sprintf($tagData, $filePath);
        }

        return $tag;
    }

    /**
     *  Builds a script which contains every flexform settings as a JavaScript option.
     */
    private function buildScript(): string
    {
        $script = '';
        $flexformSettings = $this->settings;
        if (!is_null($flexformSettings)) {
            $script = '<script type="text/javascript">Reveal.initialize({';

            /*
             * Changes the data-type of specific options, if they could have more than one data-type
             * (e.g. 'slideNumber' - boolean and string)
             */
            $this->resortTypesIfNeeded($flexformSettings);

            /**
             * @var string Option name
             */
            /**
             * @var object Option value (assigned by user)
             */
            foreach ($flexformSettings as $flexformKey => $flexformValue) {
                /*
                 * If a key (specified at start of this file) exists which is not a reveal.js-option, ignore and continue
                 */
                if (in_array($flexformKey, $this->nonJSInitOptions)) {
                    continue;
                }

                /*
                 * Don't add empty or not existing options.
                 * TODO: Verify that this is_null-check is needed or not
                 */
                if (0 == strlen($flexformValue) || is_null($flexformValue)) {
                    continue;
                }

                /*
                 * Packs both keys and values; checks their data-type to prevent JavaScript-errors
                 */
                if (in_array($flexformKey, $this->flexformDataTypes['boolean'])) {
                    $script .= "'".$flexformKey."' : ".(1 == $flexformValue ? 'true' : 'false').', ';
                } elseif (in_array($flexformKey, $this->flexformDataTypes['string'])) {
                    $script .= "'".$flexformKey."' : '".$flexformValue."', ";
                } else {
                    $script .= "'".$flexformKey."' : ".$flexformValue.', ';
                }
            }

            /*
             * Reads dependencies-file and completes script-string
             */
            $script .= file_get_contents($this->saveUserFileAndReturnPath('userPlugins.js', 'userPlugins')).'});</script>';
        }

        return $script;
    }

    /**
     * Changes the data-type of a specific key if specified conditions became true.
     *
     * @param array $flexformSettings The flexformsettings
     */
    private function resortTypesIfNeeded(array &$flexformSettings)
    {
        /*
         * Changes the data type of 'slideNumber' from boolean to string, because it's values could be "true"/"false or a string like "h.v"
         */
        if (strlen($flexformSettings['slideNumber']) > 0) {
            unset($this->flexformDataTypes['boolean'][array_search('slideNumber', $this->flexformDataTypes['boolean'])]);
            array_push($this->flexformDataTypes['string'], 'slideNumber');
        } else {
            $flexformSettings['slideNumber'] = '0'; // 0 = false
        }
    }

    /**
     * Returns the file path of the requested fileName.
     * If flexform value is not equal to the file value, it'll save it's content to the file.
     *
     * @param $fileName string
     * @param $flexformSetting string
     *
     * @return string The path of the file
     */
    private function saveUserFileAndReturnPath(string $fileName, string $flexformSetting): string
    {
        /**
         * Page id's are used for multiple reveal-presentations to handle presenatation-based overrides.
         */
        $userFile = 'fileadmin/ext/'.self::EXT_KEY.'/pid'.$GLOBALS['TSFE']->id.'_'.$fileName;

        /**
         * Fetches the text which is currently stored and the flexform text.
         */
        $fileText = file_exists($userFile) ? file_get_contents($userFile) : '';

        /**
         * Removes surrounding spaces.
         */
        $flexformText = trim($this->settings[$flexformSetting]);

        /**
         * Decodes special html-characters.
         */
        $textToSave = htmlspecialchars_decode($fileText != $flexformText ? $flexformText : $fileText);

        /**
         * Replaces special char sequences like "EXT_JS_DIR_PATH:".
         */
        $textToSave = str_replace('EXT_JS_DIR:', 'typo3conf/ext/'.self::EXT_KEY.'/Resources/Public/JavaScript/', $textToSave);

        /*
         * Saves data in file.
         */
        file_put_contents($userFile, $textToSave);

        return $userFile;
    }
}
