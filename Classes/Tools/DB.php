<?php
namespace SUDHAUS7\Sudhaus7Base\Tools;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

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
	public static function getSqlFromQuery(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query,$explainOutput = false) {

		if (VersionNumberUtility::convertVersionNumberToInteger( TYPO3_version) < 8000000) {
			$params = [];
			$GLOBALS['TYPO3_DB']->debugOuput = 2;
			if ( $explainOutput ) {
				$GLOBALS['TYPO3_DB']->explainOutput = true;
			}
			$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = true;
			$query->toArray();
			$sql = $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;

			$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = false;
			$GLOBALS['TYPO3_DB']->explainOutput        = false;
			$GLOBALS['TYPO3_DB']->debugOuput           = false;

		} else {
			$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
			/** @var \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser $queryParser */
			$queryParser = $objectManager->get( \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser::class);
			$sql = $queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL();
			$params = $queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters();
		}

		return ['sql'=>$sql,'params'=>$params];
	}

}
