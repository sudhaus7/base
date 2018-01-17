<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 17.01.18
 * Time: 15:57
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extensionmanager\Utility\ExtensionModelUtility;

class Backend {


	public static function getFrontendUrl($pageid) {
		$url = '';
		if (ExtensionManagementUtility::isLoaded( 'realurl')) {
			DB::get()->exec_SELECTgetSingleRow( '*', 'tx_realurl_urlcache', 'page_id='.$pageid);
		}
	}

}
