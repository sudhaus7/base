<?php
namespace SUDHAUS7\Sudhaus7Base\Tools;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DB {
    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    public static function get() {
        return $GLOBALS['TYPO3_DB'];
    }

	/**
	 * Generiert das SQL das ausgeführt wird aus einem QueryInterface Query z.b. in Repository Klassen
	 *
	 * Usage:
	 * $query = $this->createQuery();
	 * \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(\SUDHAUS7\Sudhaus7Base\Tools\DB::getSqlFromQuery($query));
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 *
	 * @return array
	 */
	public static function getSqlFromQuery(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query) {
		$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		/** @var \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser $queryParser */
		$queryParser = $objectManager->get( \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser::class);
		$sql = $queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL();
		$params = $queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters();
		return ['sql'=>$sql,'params'=>$params];
	}

}
