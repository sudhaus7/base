<?php
namespace SUDHAUS7\Sudhaus7Base\Tools;
class DB {
    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    public static function get() {
        return $GLOBALS['TYPO3_DB'];
    }
}