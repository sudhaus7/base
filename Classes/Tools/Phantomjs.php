<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 29/03/16
 * Time: 15:26
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class Phantomjs
{

    /**
     * @var array
     */
    private $confArr = [];

    /**
     * Phantomjs constructor.
     */
    public function __construct()
    {
        $this->confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sudhaus7_base']);
    }

    /**
     * @param $cmd string
     * @return array
     */
    public function run($cmd) {
        $log = [];
        $status = false;
        if (isset($this->confArr['PHANTOMJS']) && is_file($this->confArr['PHANTOMJS']) && is_executable($this->confArr['PHANTOMJS'])) {
            $execthis = $this->confArr['PHANTOMJS'].' '.$cmd;
            $log[] = $execthis;
            $status = exec($execthis,$log);
        }
        return array(empty($status),$log);
    }

    public function genPdf($filename,$url,$rasterize='EXT:sudhaus7_base/Resources/Private/Phantomjs/rasterize.js') {

        $rasterize = GeneralUtility::getFileAbsFileName($rasterize);
        return $this->run(sprintf('"%s" "%s" "%s" "A4" 2>&1',$rasterize,$url,$filename));

    }
    
}
