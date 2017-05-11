<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 10.05.17
 * Time: 16:39
 */

namespace SUDHAUS7\Sudhaus7Base\Backend\Filter;


use TYPO3\CMS\Core\Resource\File;
use \TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class FileSizeFilter {
	private $parameters = ['default'=>1024*1024*5];
	public function filterInlineChildren(array $parameters, \TYPO3\CMS\Core\DataHandling\DataHandler $tceMain) {
		$values = $parameters['values'];
		//$this->parameters = (int)$parameters['max_size'];
		$this->parameters = array_merge($this->parameters,$parameters);
		$cleanValues = [];
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$flashMessageService = $objectManager->get(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
		$messageQueue = $flashMessageService->getMessageQueueByIdentifier();
		if (is_array($values)) {
			foreach ($values as $value) {
				if (empty($value)) {
					continue;
				}
				$parts = \TYPO3\CMS\Core\Utility\GeneralUtility::revExplode('_', $value, 2);
				$fileReferenceUid = $parts[count($parts) - 1];
				$fileReference = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileReferenceObject($fileReferenceUid);
				$file = $fileReference->getOriginalFile();
				if ($this->isAllowed($file)) {
					$cleanValues[] = $value;
				} else {
					// Remove the erroneously created reference record again
					$tceMain->deleteAction('sys_file_reference', $fileReferenceUid);
					$messageQueue->addMessage(\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
						'Es sind nur Dateien mit einer maximalen Grösse von '.self::formatBytes( $this->getMaxSize($file)).' erlaubt. Die Datei '.$file->getName().' ist aber '.self::formatBytes( $file->getSize()).' groß. Die Datei wurde wieder aus der Verwendungsliste entfernt.',
						'Datei '.$file->getName().' ist zu groß!', // the header is optional
						\TYPO3\CMS\Core\Messaging\FlashMessage::WARNING, // the severity is optional as well and defaults to \TYPO3\CMS\Core\Messaging\FlashMessage::OK
						TRUE // optional, whether the message should be stored in the session or only in the \TYPO3\CMS\Core\Messaging\FlashMessageQueue object (default is FALSE)
					));
				}
			}
		}
		return $cleanValues;
	}
	private function isAllowed(File $file) {
		return $file->getSize() < $this->getMaxSize($file);
	}
	private function getMaxSize(File $file) {
		$ext = $file->getExtension();
		if (isset($this->parameters[$ext])) return $this->parameters[$ext];
		return $this->parameters['default'];
	}
	static function formatBytes($size, $precision = 0)
	{
		$base = log($size, 1024);
		$suffixes = array('', 'KB', 'MB', 'GB', 'TB');

		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
}
