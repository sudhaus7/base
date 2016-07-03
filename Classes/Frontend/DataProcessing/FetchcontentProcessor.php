<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 03/07/16
 * Time: 19:28
 */

namespace SUDHAUS7\Sudhaus7Base\Frontend\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class FetchcontentProcessor implements DataProcessorInterface
{

    /**
     * @var array
     */
    protected $curldefaults = array(
        CURLOPT_HEADER => 0,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
    );

    /**
     * @var \TYPO3\CMS\Core\Cache\CacheManager
     */
    private $cache = null;

    public function __construct()
    {
        $this->cache = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('bfactorfetchcontent_cache');
    }

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
    )
    {
        if (isset($processorConfiguration['field']) && !empty($processorConfiguration['field'])) {


            $field = $processorConfiguration['field'];
            if (isset($processedData['data'][$field])) {

                $link = $processedData['data'][$field];
                $processedData['data']['fetchcontenturl'] = $link;
                $processedData['data']['fetchcontentsha1'] = sha1($link);
                $processedData['data']['fetchcontentmd5'] = md5($link);
                $processedData['data']['fetchcontentmd5short'] = GeneralUtility::shortMD5($link);

                $entry = '';
                if (substr($link, 0, 4) == 'http') {


                    $cacheIdentifier = 'tx_bfactorfetchcontent-' . sha1($link);
                    $entry = '';
                    if (empty($entry)) {

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $link);
                        curl_setopt_array($ch, $this->curldefaults);
                        $entry = curl_exec($ch);
                        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($entry);
                        curl_close($ch);

                        $this->cache->set($cacheIdentifier, $entry, array('page_' . $processedData['data']['pid']), 3600);
                    }

                }

                $processedData['data']['fetchcontent'] = $entry;

            }
        }
        return $processedData;
    }

}
