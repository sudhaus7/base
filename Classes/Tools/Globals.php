<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 03/03/16
 * Time: 16:01
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


class Globals
{
    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    public static function tsfe() {
        return Tsfe::get();
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    public static function db() {
        return DB::get();
    }
}
