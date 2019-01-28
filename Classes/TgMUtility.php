<?php

use TgM\TgmReveal\Controller\RevealController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TgMUtility
{
    /**
     * Removes specific keys from the given array and moves their values to parent.
     *
     * @param array $cleanUpArray The array to clean up
     * @param array $notAllowed   Forbidden keys which should removed
     *
     * @return array The cleaned array
     */
    public static function cleanUpArray(array $cleanUpArray, array $notAllowed)
    {
        $cleanArray = [];
        foreach ($cleanUpArray as $key => $value) {
            if (in_array($key, $notAllowed)) {
                return is_array($value) ? self::cleanUpArray($value, $notAllowed) : $value;
            } elseif (is_array($value)) {
                $cleanArray[$key] = self::cleanUpArray($value, $notAllowed);
            }
        }

        return $cleanArray;
    }

    /**
     * Fetches a list of all child pid's from the starting page id.
     *
     * @param int $startPid The pid where counting starts
     * @param int $depth    The depth of child-pages
     * @param int $begin    The depth to begin
     *
     * @return array Returns an array which contains every page found. The starting page was removed via array_shift
     */
    public static function countPages(int $startPid, int $depth = 10, int $begin = 0): array
    {
        $foundPages = explode(',', GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Database\\QueryGenerator')->getTreeList($startPid, $depth, $begin, 1));
        array_shift($foundPages);

        return $foundPages;
    }

    /**
     * Converts an int-value as string like '0' or '1' to a boolean-value as string.
     *
     * @param string $booleanValue The int-value
     *
     * @return string A boolean-value as string
     */
    public static function intStringAsBooleanString(string $booleanValue): string
    {
        return '1' === $booleanValue ? 'true' : 'false';
    }

    /**
     * Returns a boolean as string if the given value has a length greater than 0.
     *
     * @param string $value The value to check
     *
     * @return string A boolean-value as string
     */
    public static function hasLengthAsString(string $value): string
    {
        return strlen(trim($value)) > 0 ? 'true' : 'false';
    }

    /**
     * Returns the extension configuration settings as an array.
     *
     * @return mixed array
     */
    public static function getEmConf()
    {
        include ExtensionManagementUtility::extPath(RevealController::EXT_KEY).'ext_emconf.php';
        /* @noinspection PhpUndefinedVariableInspection */
        return $EM_CONF[RevealController::EXT_KEY];
    }
}
