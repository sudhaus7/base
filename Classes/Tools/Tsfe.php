<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 03/03/16
 * Time: 16:00
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


class Tsfe
{
    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    public static function get() {
        return $GLOBALS['TSFE'];
    }
}
