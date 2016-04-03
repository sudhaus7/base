<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 03/04/16
 * Time: 18:47
 */

namespace SUDHAUS7\Sudhaus7Base\Frontend\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class ExplodeProcessor implements DataProcessorInterface
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
    )
    {

        if (isset($processorConfiguration['field']) && isset($processorConfiguration['splitchar'])) {
            if (isset($processedData['data'][$processorConfiguration['field']])) {
                $processedData['data'][$processorConfiguration['field']] = GeneralUtility::trimExplode($processorConfiguration['splitchar'], $processedData['data'][$processorConfiguration['field']], true);
            }
        }

        return $processedData;
    }

}
