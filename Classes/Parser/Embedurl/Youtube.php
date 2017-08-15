<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 15.08.17
 * Time: 15:54
 */

namespace SUDHAUS7\Sudhaus7Base\Parser\Embedurl;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class Youtube {
	/**
	 * @param $url
	 *
	 * @return string
	 */
	public static function parse($url) {
		$request = parse_url($url);
		$process = false;
		$process = strpos($request['host'],'youtube.') !==false ? true : $process;
		$process = strpos($request['host'],'youtu.be') !==false ? true : $process;
		if ($process) {
			$key = null;
			if (strpos($url, 'watch') !== false)
			{
				$param = [];
				parse_str($request['query'],$param);
				$key = $param['v'];
			}
			else
			{
				//$needle = '/';
				$pathar = GeneralUtility::trimExplode( '/', $request['path'], true);
				$key = array_pop($pathar);

			}
			if ($key) {
				$url = "https://www.youtube.com/embed/".$key;
			}
		}
		return $url;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public static function signal(array $data) {
		//return[self::parse($url)];
		if (isset($data['url'])) $data['url'] = self::parse( $data['url']);
		return [$data];
	}
}
