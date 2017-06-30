<?php

namespace TgM\TgmReveal\Hooks;

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
use TgM\TgmReveal\Controller\RevealController;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook to render the preview of custom content elements in the backend
 */
class BackendViewDrawItemHook implements PageLayoutViewDrawItemHookInterface {

	/**
	 * Rendering
	 *
	 * @param PageLayoutView $parentObject
	 * @param bool $drawItem
	 * @param string $headerContent
	 * @param string $itemContent
	 * @param array $row
	 */
	public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row) {
		include_once ExtensionManagementUtility::extPath(RevealController::EXT_KEY) . 'Classes/TgMUtility.php';

		/**
		 * Iterates every content element on the page and modifies elements with ctype 'list' and listtype 'tgm_reveal_reveal'
		 */
		if(!($row['CType'] === 'list' && $row['list_type'] === 'tgmreveal_reveal')) return;

		$drawItem = false;
		$headerContent = '<strong>TgM-Reveal</strong><br/>';

		/**
		 * Default values
		 */
		$settings = ['extName' => 'TgM-Reveal', 'bePreview' => []];

		/**
		 * Fetches all Flexform settings and filters specific
		 */
		$flexform = \TgMUtility::cleanUpArray(GeneralUtility::xml2array($row['pi_flexform']), ['data', 'sDEF', 'lDEF', 'vDEF']);
		$this->filterFlexformSettings($settings['bePreview'], $flexform);

		/**
		 * Loads the template file for the backend preview
		 */
		$fluidTmplFilePath = GeneralUtility::getFileAbsFileName('typo3conf/ext/' . RevealController::EXT_KEY . '/Resources/Private/Templates/BackendTemplate.html');
		$fluidTmpl = GeneralUtility::makeInstance('TYPO3\CMS\Fluid\View\StandaloneView');
		$fluidTmpl->setTemplatePathAndFilename($fluidTmplFilePath);

		/**
		 * Assigns the flexform values for use in the backend preview
		 */
		$fluidTmpl->assignMultiple(['preview' => $settings['bePreview'], 'extName' => $settings['extName']]);

		$itemContent = $parentObject->linkEditContent($fluidTmpl->render(), $row);
	}

	/**
	 * Filters required flexform settings
	 *
	 * @param array $bePreview
	 * @param array $flexform
	 *
	 * @internal param array $cleanUpArray
	 */
	private function filterFlexformSettings(array &$bePreview, array $flexform) {
		$bePreview['General']['settings'] = $this->getGeneralPreviewSettings($flexform['general']);
		$bePreview['Presentation']['settings'] = $this->getPresentationPreviewSettings($flexform['presentation']);
		$bePreview['Movement']['settings'] = $this->getMovementPreviewSettings($flexform['movement']);
		$bePreview['Parallax']['settings'] = $this->getParallaxPreviewSettings($flexform['parallax']);
		$bePreview['Other']['settings'] = $this->getOtherPreviewSettings(['userFiles' => $flexform['userFiles'], 'other' => $flexform['other']]);

		$emConf = \TgMUtility::getEmConf();
		$bePreview['emConf']['extVersion'] = $emConf['version'];
		$bePreview['emConf']['extDescription'] = $emConf['description'];
	}

	/**
	 * Counts the amount of presentation pages and their sub-pages for future use in backend preview
	 *
	 * @return array
	 */
	private function countPagesAndSubPages(): array {
		$pageStatistics = ['mainPageCount' => 0, 'subPageCount' => 0];

		/**
		 * Counts all sub-pages (including the starting page-id itself)
		 * $_GET['id'] = current page
		 */
		$mainPages = \TgMUtility::countPages($_GET['id'], 1);
		$subPageCount = 0;
		foreach ($mainPages as $mainPageId) {
			$subPages = \TgMUtility::countPages($mainPageId, 1);
			/**
			 * Removes the first value because It's the $mainPageId itself
			 */

			/**
			 * If there's no sub-page, continue
			 */
			if(count($subPages) === 0) continue;

			/**
			 * Counts the sub pages
			 */
			$subPageCount += count($subPages);
		}

		/**
		 * Counts the number of main-pages and removes one (because of starting page-id)
		 */
		$pageStatistics['mainPageCount'] = count($mainPages);
		$pageStatistics['subPageCount'] = $subPageCount;

		return $pageStatistics;
	}

	/**
	 * Collects general settings.
	 * @param array $settings The array from flexform.
	 * @return array The final array to print it's values in the preview.
	 */
	private function getGeneralPreviewSettings(array $settings): array {
		$general = [];
		$general['Controls'] = \TgMUtility::intStringAsBooleanString($settings['settings.controls']);
		$general['Progress'] = \TgMUtility::intStringAsBooleanString($settings['settings.progress']);

		$general['Slide Number'] = $settings['settings.slideNumber'];

		$general['History'] = \TgMUtility::intStringAsBooleanString($settings['settings.history']);
		$general['Keyboard'] = \TgMUtility::intStringAsBooleanString($settings['settings.keyboard']);
		$general['Overview'] = \TgMUtility::intStringAsBooleanString($settings['settings.overview']);
		$general['Center'] = \TgMUtility::intStringAsBooleanString($settings['settings.center']);
		$general['Touch'] = \TgMUtility::intStringAsBooleanString($settings['settings.touch']);
		$general['Loop'] = \TgMUtility::intStringAsBooleanString($settings['settings.loop']);

		$general['RTL'] = \TgMUtility::intStringAsBooleanString($settings['settings.rtl']);
		$general['Shuffle'] = \TgMUtility::intStringAsBooleanString($settings['settings.shuffle']);
		$general['Fragments'] = \TgMUtility::intStringAsBooleanString($settings['settings.fragments']);
		$general['Embedded'] = \TgMUtility::intStringAsBooleanString($settings['settings.embedded']);
		$general['Help'] = \TgMUtility::intStringAsBooleanString($settings['settings.help']);
		$general['Show Notes'] = \TgMUtility::intStringAsBooleanString($settings['settings.showNotes']);
		$general['Hide address bar'] = \TgMUtility::intStringAsBooleanString($settings['settings.hideAddressBar']);
		$general['Preview links'] = \TgMUtility::intStringAsBooleanString($settings['settings.previewLinks']);
		$general['View distance'] = $settings['settings.viewDistance'];
		return $general;
	}

	/**
	 * Collects preview settings.
	 * @param array $settings The array from flexform.
	 * @return array The final array to print it's values in the preview.
	 */
	private function getPresentationPreviewSettings(array $settings): array {
		$presentation = [];
		$presentation['Theme'] = ucfirst($settings['settings.theme']);
		$presentation['Width'] = $settings['settings.width'] . 'px';
		$presentation['Height'] = $settings['settings.height'] . 'px';
		$presentation['Margin'] = $settings['settings.margin'];
		$presentation['Min. Scale'] = $settings['settings.minScale'];
		$presentation['Max. Scale'] = $settings['settings.maxScale'];
		return $presentation;
	}

	/**
	 * Collects movement settings.
	 * @param array $settings The array from flexform.
	 * @return array The final array to print it's values in the preview.
	 */
	private function getMovementPreviewSettings(array $settings): array {
		$movement = [];
		$movement['Transition'] = ucfirst($settings['settings.transition']);
		$movement['Transition speed'] = ucfirst($settings['settings.transitionSpeed']);
		$movement['Background Transition'] = ucfirst($settings['settings.backgroundTransition']);
		$movement['Auto slide'] = $settings['settings.autoSlide'];
		$movement['Auto slide stoppable'] = \TgMUtility::intStringAsBooleanString($settings['settings.autoSlideStoppable']);
		$movement['Auto slide method'] = $settings['settings.autoSlideMethod'];
		$movement['Mouse wheel'] = \TgMUtility::intStringAsBooleanString($settings['settings.mouseWheel']);
		return $movement;
	}

	/**
	 * Collects parallax settings.
	 * @param array $settings The array from flexform.
	 * @return array The final array to print it's values in the preview.
	 */
	private function getParallaxPreviewSettings(array $settings): array {
		$parallax = [];
		$parallax['Parallax background image'] = $settings['settings.parallaxBackgroundImage'];
		$parallax['Parallax background size'] = $settings['settings.parallaxBackgroundSize'];
		$parallax['Parallax background horizontal'] = $settings['settings.parallaxBackgroundHorizontal'];
		$parallax['Parallax background vertical'] = $settings['settings.parallaxBackgroundVertical'];
		return $parallax;
	}

	/**
	 * Collects other settings.
	 * @param array $settings The array from flexform.
	 * @return array The final array to print it's values in the preview.
	 */
	private function getOtherPreviewSettings(array $settings): array {
		$other['Custom CSS'] = \TgMUtility::hasLengthAsString($settings['userFiles']['settings.userCSS']);
		$other['Custom JavaScript'] = \TgMUtility::hasLengthAsString($settings['userFiles']['settings.userJS']);
		$other['Enable Fancybox'] = \TgMUtility::intStringAsBooleanString($settings['other']['settings.enableFancybox']);
		$other['Disable browser zooming'] = \TgMUtility::intStringAsBooleanString($settings['other']['settings.disableBrowserZooming']);

		$pageStatistics = $this->countPagesAndSubPages();
		$other['Main pages'] = $pageStatistics['mainPageCount'];
		$other['Sub pages'] = $pageStatistics['subPageCount'];
		return $other;
	}
}