<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 17.01.18
 * Time: 15:57
 */

namespace SUDHAUS7\Sudhaus7Base\Tools;


use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extensionmanager\Utility\ExtensionModelUtility;

class Backend {


	public static function getFrontendUrl($pageid) {
		$uri = 'index.php?id='.$pageid;
		if (ExtensionManagementUtility::isLoaded( 'realurl')) {
			$row = DB::get()->exec_SELECTgetSingleRow( '*', 'tx_realurl_urlcache', 'page_id='.$pageid);
			if ($row) {
				if (!empty($row['speaking_url'])) $uri =$row['speaking_url'];
			}
		}
		$rl = BackendUtility::BEgetRootLine( $pageid);
		$domain = $_SERVER['HTTP_HOST'];
		foreach ($rl as $p) {
			if ($p['is_siteroot'] > 0) {
				$row = DB::get()->exec_SELECTgetSingleRow( '*', 'sys_domain', 'hidden=0 and redirectTo="" and pid='.$p['uid'],'','sorting asc');
				if ($row) {
					$domain  = $row['domainName'];
				}
			}
		}
		return 'http://'.$domain.'/'.$uri;
	}

}
