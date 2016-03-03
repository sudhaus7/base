<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 03/03/16
 * Time: 16:23
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


class Cache
{
    public static function clearPage($id='all')
    {
        /** @var \TYPO3\CMS\Core\Cache\CacheManager $cacheManager */
        $cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Cache\CacheManager::class
        );
        if ($id == 'all') {
            $cacheManager->flushCachesInGroup('pages');
        } else {
            $cacheManager->flushCachesInGroupByTag('pages', 'pageId_' . $id);
        }
    }
}
