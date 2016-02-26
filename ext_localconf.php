<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'Classes/Tools/Plugins.php');


$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Package\\DependencyResolver'] = array(
    'className' => 'SUDHAUS7\\Sudhaus7Base\\Typo3fixes\\Composerrequire'
);
