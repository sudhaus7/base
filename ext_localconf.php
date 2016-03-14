<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'Classes/Tools/Plugins.php');



$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys']['s7_developer'] = array(
    'EXT:sudhaus7_base/Classes/Cli/Developer.php',
    '_CLI_lowlevel'
);
