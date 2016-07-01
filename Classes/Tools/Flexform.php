<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 30/06/16
 * Time: 19:02
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


class Flexform
{

    public static function flatten($data)
    {
        $tmp = $data['data'];
        $data = [];
        foreach ($tmp as $k => $a) {
            foreach ($a as $kk => $aa) {
                //$data = array_merge($data,$aa);
                foreach ($aa as $name => $value) {
                    $data[$name] = $value['vDEF'];
                }
            }
        }
        return $data;
    }

    public static function blowup($data)
    {
        $ret = [];

        foreach ($data as $k => $v) {
            $ret['sDEF']['lDEF'][$k]['vDEF'] = $v;
        }
        return ['data' => $ret];
    }


}
