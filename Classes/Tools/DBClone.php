<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 05/04/16
 * Time: 16:32
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


class DBClone
{
    public static function tt_content($row)
    {

        $uid = $row['uid'];

        $row['crdate'] = time();
        $row['tstamp'] = time();
        $row['t3_origuid'] = $row['uid'];
        unset($row['uid']);
        Globals::db()->exec_INSERTquery('tt_content', $row);
        $newid = Globals::db()->sql_insert_id();
        $row['uid'] = $newid;

        if ($row['image'] > 0) {
            $res = Globals::db()->exec_SELECTquery('*', 'sys_file_reference', 'uid_foreign=' . $uid . ' and tablenames="tt_content" and fieldname="image" and deleted=0', '', 'sorting asc');
            while ($ref = Globals::db()->sql_fetch_assoc($res)) {
                unset($ref['uid']);
                $ref['uid_foreign'] = $newid;
                Globals::db()->exec_INSERTquery('sys_file_reference', $ref);
            }
        }
        if ($row['assets'] > 0) {
            $res = Globals::db()->exec_SELECTquery('*', 'sys_file_reference', 'uid_foreign=' . $uid . ' and tablenames="tt_content" and fieldname="assets" and deleted=0', '', 'sorting asc');
            while ($ref = Globals::db()->sql_fetch_assoc($res)) {
                unset($ref['uid']);
                $ref['uid_foreign'] = $newid;
                Globals::db()->exec_INSERTquery('sys_file_reference', $ref);
            }
        }
        if ($row['media'] > 0) {
            $res = Globals::db()->exec_SELECTquery('*', 'sys_file_reference', 'uid_foreign=' . $uid . ' and tablenames="tt_content" and fieldname="media" and deleted=0', '', 'sorting asc');
            while ($ref = Globals::db()->sql_fetch_assoc($res)) {
                unset($ref['uid']);
                $ref['uid_foreign'] = $newid;
                Globals::db()->exec_INSERTquery('sys_file_reference', $ref);
            }
        }
        return $row;
    }
}
