<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 15.08.17
 * Time: 16:01
 */

namespace SUDHAUS7\Sudhaus7Base\Parser\Embedurl;


class Vimeo {
	/**
	 * @param $url
	 *
	 * @return string
	 */
	public static function parse($url) {
		$request = parse_url($url);
		$process = false;
		$process = strpos($request['host'],'vimeo.') !==false ? true : $process;

		if ($process) {
			$search = '/';
			$last = strrpos($url, $search);
			if ($last === false) {
				$last = 0;
			} else {
				$last = $last + 1;
			}
			$url = "https://player.vimeo.com/video/".substr($url, $last);

		}
		return $url;
	}


	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public static function signal(array $data) {
		if (isset($data['url'])) $data['url'] = self::parse( $data['url']);
		return [$data];
	}
}
