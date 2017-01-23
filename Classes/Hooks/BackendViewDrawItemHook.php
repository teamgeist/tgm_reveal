<?php
namespace TgM\TgmReveal\Hooks;

use TgM\TgmReveal\Controller\RevealController;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook to render the preview of custom content elements in the backend
 */
class BackendViewDrawItemHook implements PageLayoutViewDrawItemHookInterface {

	/**
	 * Rendering
	 *
	 * @param PageLayoutView $parentObject
	 * @param bool           $drawItem
	 * @param string         $headerContent
	 * @param string         $itemContent
	 * @param array          $row
	 */
	public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row) {
		/**
		 * Iterates every content element on the page and modifies elements with ctype 'list'
		 */
		if($row['CType'] !== 'list' && $row['list_type'] !== 'tgmreveal_reveal') return;

		$drawItem = false;
		$headerContent = '<strong>TgM-Reveal</strong><br/>';

		/**
		 * Default values
		 */
		$settings = ['extName' => 'TgM-Reveal', 'bePreview' => []];

		/**
		 * Fetches all Flexform settings and filters specific
		 */
		$flexform = $this->cleanUpArray(GeneralUtility::xml2array($row['pi_flexform']), ['data', 'sDEF', 'lDEF', 'vDEF']);
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
		$bePreview['width'] = $flexform['presentation']['settings.width'];
		$bePreview['height'] = $flexform['presentation']['settings.height'];

		$bePreview['theme'] = ucfirst($flexform['presentation']['settings.theme']);
		$bePreview['transition'] = ucfirst($flexform['movement']['settings.transition']);

		$bePreview['parallax'] = $this->hasLengthAsString($flexform['parallax']['settings.parallaxBackgroundImage']);
		$bePreview['customCSS'] = $this->hasLengthAsString($flexform['userFiles']['settings.userCSS']);
		$bePreview['customJavaScript'] = $this->hasLengthAsString($flexform['userFiles']['settings.userJS']);

		$bePreview['autoSlide'] = $this->intStringAsBooleanString($flexform['movement']['settings.autoSlide']);
		$bePreview['mouseWheel'] = $this->intStringAsBooleanString($flexform['movement']['settings.mouseWheel']);
		$bePreview['enableFancybox'] = $this->intStringAsBooleanString($flexform['other']['settings.enableFancybox']);
		$bePreview['disableBrowserZooming'] = $this->intStringAsBooleanString($flexform['other']['settings.disableBrowserZooming']);

		$pageStatistics = $this->countPagesAndSubPages();
		$bePreview['mainPageCount'] = $pageStatistics['mainPageCount'];
		$bePreview['subPageCount'] = $pageStatistics['subPageCount'];
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
		$mainPages = $this->countPages($_GET['id'], 1);
		$subPageCount = 0;
		foreach ($mainPages as $mainPageId) {
			$subPages = $this->countPages($mainPageId, 1);
			/**
			 * Removes the first value because It's the $mainPageId itself
			 */

			/**
			 * If there's no sub-page, continue
			 */
			if(count($subPages) === 0) continue;

			/**
			 * Removes the first sub-page (it's already count as main-page)
			 */
			array_shift($subPages);

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
	 * Removes specific keys from the given array and moves their values to parent
	 *
	 * @param array $cleanUpArray The array to clean up
	 * @param array $notAllowed Forbidden keys which should removed
	 *
	 * @return array The cleaned array
	 */
	private function cleanUpArray(array $cleanUpArray, array $notAllowed) {
		$cleanArray = [];
		foreach ($cleanUpArray as $key => $value) {
			if(in_array($key, $notAllowed)) {
				return is_array($value) ? self::cleanUpArray($value, $notAllowed) : $value;
			} else if(is_array($value)) {
				$cleanArray[$key] = self::cleanUpArray($value, $notAllowed);
			}
		}
		return $cleanArray;
	}

	/**
	 * Fetches a list of all child pid's from the starting page id
	 *
	 * @param int $startPid The pid where counting starts
	 * @param int $depth The depth of child-pages
	 * @param int $begin The depth to begin
	 *
	 * @return array Returns an array which contains every page found. The starting page was removed via array_shift
	 */
	private function countPages(int $startPid, int $depth = 10, int $begin = 0): array {
		$foundPages = explode(',', GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Database\\QueryGenerator')->getTreeList($startPid, $depth, $begin, 1));
		array_shift($foundPages);
		return $foundPages;
	}

	/**
	 * Converts an int-value as string like '0' or '1' to a boolean-value as string
	 *
	 * @param string $booleanValue The int-value
	 *
	 * @return string A boolean-value as string
	 */
	private function intStringAsBooleanString(string $booleanValue): string {
		return $booleanValue === '1' ? 'true' : 'false';
	}

	/**
	 * Returns a boolean as string if the given value has a length greater than 0
	 *
	 * @param string $value The value to check
	 *
	 * @return string A boolean-value as string
	 */
	private function hasLengthAsString(string $value): string {
		return strlen(trim($value)) > 0 ? 'true' : 'false';
	}
}