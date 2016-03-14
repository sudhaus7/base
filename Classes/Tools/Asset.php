<?php

namespace SUDHAUS7\Sudhaus7Base\Tools;

/**
 * Class Asset
 *
 * @package SUDHAUS7\Sudhaus7Base\Tools
 */
/**
 * Class Asset
 * @package SUDHAUS7\Sudhaus7Base\Tools
 */
class Asset {

    /**
     * @param string $ext The Extensionkey
     * @param array $list A list with filenames relative to Resources/Public of $ext
     * @param bool $footer
     * @param bool $return return the content instead of adding to TSFE
     * @return mixed
     */
    public static function add($ext, array $list, $footer = false, $return=false) {

        $content = '';
        foreach ($list as $e) {
            $a = explode('.', $e);
            $suffix = array_pop($a);
            if (method_exists(__CLASS__, $suffix)) {
                $content .= self::$suffix($ext, $e,$footer,$return);
            }
        }
        if ($return) return $content;
    }

    /**
     * @param string $ext The Extensionkey
     * @param string $file A filename relative to Resources/Public of $ext or an URL
     * @param bool $footer
     * @param bool $return return the content instead of adding to TSFE
     * @return mixed
     */
    public static function js($ext, $file, $footer = false, $return=false) {

        $content = '';
        if (is_file(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($ext) . 'Resources/Public/' . $file)) {
            $mtime = filemtime(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($ext) . 'Resources/Public/' . $file);
            $content .= '
<script type="text/javascript" src="typo3conf/ext/' . $ext . '/Resources/Public/' . $file . '?' . $mtime . '"></script>
';
        } else if (substr($file, 0, 4) == 'http' || substr($file, 0, 2) == '//') {
            $content .= '
<script type="text/javascript" src="' . $file . '"></script>
';
        }
        if ($return) {
            return $content;
        } else {
            self::addCached($ext,$content,$footer);
        }
    }

    /**
     * @param string $ext The Extensionkey
     * @param string $file A filename relative to Resources/Public of $ext or an URL
     * @param bool $footer
     * @param bool $return return the content instead of adding to TSFE
     * @return mixed
     */
    public static function css($ext, $file,$footer = false, $return=false) {
        $content = '';
        if (is_file(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($ext) . 'Resources/Public/' . $file)) {
            $mtime = filemtime(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($ext) . 'Resources/Public/' . $file);
            $content .= '
<link rel="stylesheet" type="text/css" href="typo3conf/ext/' . $ext . '/Resources/Public/' . $file . '?' . $mtime . '" media="screen"/>
';
        } else if (substr($file, 0, 4) == 'http' || substr($file, 0, 2) == '//') {
            $content .= '
<link rel="stylesheet" type="text/css" href="' . $file . '" media="screen"/>
';
        }
        if ($return) {
            return $content;
        } else {
            self::addCached($ext,$content,$footer);
        }
    }

    /**
     * @param string $ext
     * @param string $content
     * @param string $footer
     * @return void
     */
    private static function addCached($ext, $content, $footer) {
        $var = "additionalHeaderData";
        if ($footer) {
            $var = "additionalFooterData";
        }
        if (!isset($ext,$GLOBALS['TSFE']->{$var})) $GLOBALS['TSFE']->{$var}[$ext] = '';
        $GLOBALS['TSFE']->{$var}[$ext] .= $content;
    }

}
