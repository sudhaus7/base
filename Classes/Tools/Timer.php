<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 24/11/2016
 * Time: 15:01
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


class Timer
{
    public static function start() {
        if (!isset($GLOBALS['SUDHAUS7BASE_TOOLS_TIMER'])) $GLOBALS['SUDHAUS7BASE_TOOLS_TIMER'] = [];
        $GLOBALS['SUDHAUS7BASE_TOOLS_TIMER']['start'] = microtime(true);
    }
    public static function end() {
        self::ping('end');
    }
    public static function ping($id) {

        $GLOBALS['SUDHAUS7BASE_TOOLS_TIMER'][$id] = microtime(true);
    }
    public static function get() {

        $stat = [];
        $last = 0;
        foreach ($GLOBALS['SUDHAUS7BASE_TOOLS_TIMER'] as $k=>$v) {
            $stat[] = $k.':'.$v .' - +' .($v-$last);
            $last = $v;
        }
        $first = array_shift($GLOBALS['SUDHAUS7BASE_TOOLS_TIMER']);
        $last = array_pop($GLOBALS['SUDHAUS7BASE_TOOLS_TIMER']);
        $stat[] = 'overall : '.($last-$first);
        return $stat;
    }
    public static function writestat($file,$stat) {
        file_put_contents($file, implode("\n",$stat));
    }
}
