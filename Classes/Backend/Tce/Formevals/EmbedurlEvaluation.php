<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 15.08.17
 * Time: 14:57
 */

namespace SUDHAUS7\Sudhaus7Base\Backend\Tce\Formevals;



use TYPO3\CMS\Core\Utility\GeneralUtility;

class EmbedurlEvaluation {


	/**
	 * JavaScript code for client side validation/evaluation
	 *
	 * @return string JavaScript code for client side validation/evaluation
	 */
	public function returnFieldJS() {
		return '
         return value;
      ';
	}

	/**
	 * @param $value
	 * @param $is_in
	 * @param $set
	 *
	 * @return mixed
	 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
	 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
	 */
	public function evaluateFieldValue($value, $is_in, &$set) {
		/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
		$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
		$data = ['url'=>$value];
		$parsed = $signalSlotDispatcher->dispatch( __CLASS__, 'evaluateFieldValue',[$data]);
		$url = $parsed[0]['url'];
		//$url = $this->checkVideoSource($value);
		if ($url != $value ) $set=true;
		return $url;
	}

	/**
	 * Server-side validation/evaluation on opening the record
	 *
	 * @param array $parameters Array with key 'value' containing the field value from the database
	 * @return string Evaluated field value
	 */
	public function deevaluateFieldValue(array $parameters) {
		return $parameters['value'];
	}

	private function checkVideoSource($video) {



		$platform = null;
		$platform = strpos($video,'youtube.') > 0 ? 'youtube' : $platform;
		$platform = strpos($video,'youtu.be') > 0 ? 'youtube' : $platform;
		$platform = strpos($video,'vimeo.') > 0 ? 'vimeo' : $platform;
		switch ($platform) {
			case 'youtube':
				$link = $this->generateYoutube($video);
				break;
			case 'vimeo':
				$link = $this->generateVimeo($video);
				break;
			default:
				$link = $video;
				break;
		}
		return $link;
	}
	private function generateVimeo($video) {
		$search = '/';
		$last = strrpos($video, $search);
		if ($last === false) {
			$last = 0;
		} else {
			$last = $last + 1;
		}
		$vimeo = substr($video, $last);

		return "https://player.vimeo.com/video/".$vimeo;
	}

	private function generateYoutube($video) {
		if (strpos($video, 'youtu'))
		{
			if (strpos($video, 'watch'))
			{

				$query = parse_url($video,PHP_URL_QUERY);
				$param = [];
				parse_str($query,$param);
				$youtube = $param['watch'];

			}
			else
			{
				//$needle = '/';
				$path = parse_url($video,PHP_URL_PATH);
				$pathar = GeneralUtility::trimExplode( '/', $path, true);
				$youtube = array_pop($pathar);

			}
		}
		else
		{
			$youtube = $video;
		}

		return "https://www.youtube.com/embed/".$youtube;
	}

}
