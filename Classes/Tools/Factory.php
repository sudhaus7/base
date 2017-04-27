<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 18/05/16
 * Time: 12:04
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


class Factory
{
    public static function FalByForeign($uid_foreign, $table, $field)
    {
        $sql = sprintf('
            uid_foreign=%1$d
            AND tablenames="%2$s"
            AND fieldname="%3$s"
            AND deleted=0
            AND hidden=0
        ', $uid_foreign, $table, $field);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file_reference', $sql, '', 'sorting_foreign ASC');
        $images = array();
        while ($ret = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

            $resfile = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file', 'uid=' . $ret['uid_local']);
            $rowfile = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resfile);

            $resstorage = $GLOBALS['TYPO3_DB']->sql_query('select ExtractValue(configuration,\'//field[@index="basePath"]/value\') as base from sys_file_storage where uid=' . $rowfile['storage']);
            $rowstorage = $GLOBALS['TYPO3_DB']->sql_fetch_row($resstorage);
            $ret['identifier'] = str_replace('//', '/', $rowstorage[0] . $rowfile['identifier']);
            $origRet = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file_metadata', 'file=' . $ret['uid_local']);
            if($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($origRet)) {
	            foreach ($row as $k => $v) {
	                if (isset($ret[$k]) && empty($ret[$k])) $ret[$k] = $v;
	                if (!isset($ret[$k])) $ret[$k] = $v;
	            }
            } else {
	            list($width, $height, $type, $attr) = \getimagesize( PATH_site  . $ret['identifier']);
	            $ret['width']=$width;
	            $ret['height']=$height;
            }
            $images[] = $ret;
        }
        return $images;
    }

    public static function FalByLocal($uid_local)
    {
        $sql = sprintf('
            uid_local=%1$d
            AND deleted=0
            AND hidden=0
        ', $uid_local);
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file_reference', $sql, '', 'sorting_foreign ASC');
        $images = array();
        while ($ret = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

            $resfile = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file', 'uid=' . $ret['uid_local']);
            $rowfile = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resfile);

            $resstorage = $GLOBALS['TYPO3_DB']->sql_query('select ExtractValue(configuration,\'//field[@index="basePath"]/value\') as base from sys_file_storage where uid=' . $rowfile['storage']);
            $rowstorage = $GLOBALS['TYPO3_DB']->sql_fetch_row($resstorage);
            $ret['identifier'] = str_replace('//', '/', $rowstorage[0] . $rowfile['identifier']);
            $origRet = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file_metadata', 'file=' . $ret['uid_local']);
            $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($origRet);
            foreach ($row as $k => $v) {
                if (isset($ret[$k]) && empty($ret[$k])) $ret[$k] = $v;
                if (!isset($ret[$k])) $ret[$k] = $v;
            }
            $images[] = $ret;
        }
        return $images;
    }
}
