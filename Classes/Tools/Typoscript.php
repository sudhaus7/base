<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 01/03/16
 * Time: 16:55
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Typoscript
{

    public static function parse ($s) {

        /** @var  TypoScriptParser $oTSparser */
        $oTSparser = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\Parser\\TypoScriptParser');
        $oTSparser->parse($s);
        return $oTSparser->setup;
    }

    public static function fold (array $a,$i=0,$keys='') {
        $c = '';
        foreach ($a as $k=>$v) {
            if (is_array($v)) {

                if (sizeof($v) > 1) {
                    $c .= "\n".str_repeat('  ', $i).$keys.substr($k,0,-1).' {';
                    $c .= str_repeat('  ', $i).self::fold($v,$i+1);
                    $c .= str_repeat('  ', $i).'}';
                } else {
                    $c .= str_repeat('  ', $i).self::fold($v,$i,$keys.$k);
                }
            } else {
                if (empty($keys)) {
                    $c .= "\n".str_repeat('  ', $i).$k.' = '.$v;
                } else {
                    $c .= "\n".str_repeat('  ', $i).$keys.$k.' = '.$v;
                }
            }
        }
        return $c."\n";
    }
}
