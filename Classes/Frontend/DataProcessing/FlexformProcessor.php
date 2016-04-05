<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 23/03/16
 * Time: 13:47
 */

namespace SUDHAUS7\Sudhaus7Base\Frontend\DataProcessing;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class FlexformProcessor implements DataProcessorInterface
{

    /**
     * Process field data to split in an array
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        if (isset($processedData['data']['sudhaus7_flexform']) && !empty($processedData['data']['sudhaus7_flexform']) && !is_array($processedData['data']['sudhaus7_flexform'])) {
            $processedData['data']['sudhaus7_flexform'] = self::run($processedData['data']['sudhaus7_flexform'],isset($processorConfiguration['flatten']) && $processorConfiguration['flatten']);
            if (isset($processorConfiguration['merge']) && $processorConfiguration['merge'] && is_array($processedData['data']['sudhaus7_flexform'])) {
                $processedData['data'] = array_merge($processedData['data'], $processedData['data']['sudhaus7_flexform']);
            }
        }

        return $processedData;
    }

    public static function run($s,$flatten = false) {
        //$data = array();
        $data = GeneralUtility::xml2array($s);
        if ($flatten) {
            $tmp = $data['data'];
            $data = [];
            foreach ($tmp as $k=>$a) {
                foreach ($a as $kk => $aa) {
                    //$data = array_merge($data,$aa);
                    foreach ($aa as $name => $value) {
                        $data[$name]=$value['vDEF'];
                    }
                }
            }

        }
        return $data;
    }
}
