<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
$extConfig  = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sudhaus7_base']);



require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'Classes/Tools/Plugins.php');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys']['s7_developer'] = array(
    function () {
        $cleanerObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\SUDHAUS7\Sudhaus7Base\Cli\Developer::class);
        $cleanerObj->cli_main($_SERVER['argv']);
    },
    '_CLI_lowlevel'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys']['s7_translatexml'] = array(
    function () {
        $cleanerObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\SUDHAUS7\Sudhaus7Base\Cli\Translatexml::class);
        $cleanerObj->cli_main($_SERVER['argv']);
    },
    '_CLI_lowlevel'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['cliKeys']['sasscompiler'] = array(
    function () {
        $cleanerObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\SUDHAUS7\Sudhaus7Base\Cli\Sasscompile::class);
        $cleanerObj->cli_main($_SERVER['argv']);
    },
    '_CLI_lowlevel'
);
if (isset($extConfig['INCLUDEMAILPOSTPROCESSOR']) && isset($extConfig['INCLUDEMAILPOSTPROCESSOR']) > 0) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Form\PostProcess\MailPostProcessor::class] = [
        'className' => SUDHAUS7\Sudhaus7Base\PostProcess\MailPostProcessor::class,
    ];
}

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['sudhaus7fetchcontent_cache'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['sudhaus7fetchcontent_cache'] = array(
        'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
        'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend',
        'groups' => array('pages'),
        'options' => array('defaultLifetime' => 3600),
    );
}
