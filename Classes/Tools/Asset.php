<?php

namespace SUDHAUS7\Sudhaus7Base\Tools;

/**
 * Class Asset
 *
 * @package SUDHAUS7\Sudhaus7Base\Tools
 */
class Asset {

    /**
     * @param string $ext The Extensionkey
     * @param array $list A list with filenames relative to Resources/Public of $ext
     * @param bool $footer
     */
    public static function add($ext, array $list, $footer = false) {
        $var = "additionalHeaderData";
        if ($footer) {
            $var = "additionalFooterData";
        }
        if (!isset($ext,$GLOBALS['TSFE']->{$var})) $GLOBALS['TSFE']->{$var}[$ext] = '';
        foreach ($list as $e) {
            $a = explode('.', $e);
            $suffix = array_pop($a);
            if (method_exists(__CLASS__, $suffix)) {
                self::$suffix($ext, $e,$footer);
            }
        }
    }

    /**
     * @param string $ext The Extensionkey
     * @param string $file A filename relative to Resources/Public of $ext or an URL
     * @param bool $footer
     */
    public static function js($ext, $file, $footer = false) {
        $var = "additionalHeaderData";
        if ($footer) {
            $var = "additionalFooterData";
        }
        if (!isset($ext,$GLOBALS['TSFE']->{$var})) $GLOBALS['TSFE']->{$var}[$ext] = '';
        if (is_file(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($ext) . 'Resources/Public/' . $file)) {
            $mtime = filemtime(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($ext) . 'Resources/Public/' . $file);
            $GLOBALS['TSFE']->{$var}[$ext] .= '
<script type="text/javascript" src="typo3conf/ext/' . $ext . '/Resources/Public/' . $file . '?' . $mtime . '"></script>
';
        } else if (substr($file, 0, 4) == 'http' || substr($file, 0, 2) == '//') {
            $GLOBALS['TSFE']->{$var}[$ext] .= '
<script type="text/javascript" src="' . $file . '"></script>
';
        }
    }

    /**
     * @param string $ext The Extensionkey
     * @param string $file A filename relative to Resources/Public of $ext or an URL
     * @param bool $footer
     */
    public static function css($ext, $file,$footer = false) {
        $var = "additionalHeaderData";
        if ($footer) {
            $var = "additionalFooterData";
        }
        if (!isset($ext,$GLOBALS['TSFE']->{$var})) $GLOBALS['TSFE']->{$var}[$ext] = '';
        if (is_file(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($ext) . 'Resources/Public/' . $file)) {
            $mtime = filemtime(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($ext) . 'Resources/Public/' . $file);
            $GLOBALS['TSFE']->{$var}[$ext] .= '
<link rel="stylesheet" type="text/css" href="typo3conf/ext/' . $ext . '/Resources/Public/' . $file . '?' . $mtime . '" media="screen"/>
';
        } else if (substr($file, 0, 4) == 'http' || substr($file, 0, 2) == '//') {
            $GLOBALS['TSFE']->{$var}[$ext] .= '
<link rel="stylesheet" type="text/css" href="' . $file . '" media="screen"/>
';
        }
    }
}