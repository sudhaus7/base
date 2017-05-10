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

class FileSizeFilter {
	private $maxsize = 0;
	public function filterInlineChildren(array $parameters, \TYPO3\CMS\Core\DataHandling\DataHandler $tceMain) {
		$values = $parameters['values'];
		$this->maxsize = (int)$parameters['max_size'];
		if ($this->maxsize < 1) $this->maxsize = 1024*10;
		$cleanValues = [];
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
				}
			}
		}
		return $cleanValues;
	}
	private function isAllowed(File $file) {
		$size = $file->getSize();
		if ($size < $this->maxsize) return true;
		return false;
		return $file->getSize() < $this->maxsize;

	}
}
