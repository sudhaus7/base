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
if (isset($extConfig['INCLUDEMAILPOSTPROCESSOR']) && $extConfig['INCLUDEMAILPOSTPROCESSOR'] > 0) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Form\PostProcess\MailPostProcessor::class] = [
        'className' => SUDHAUS7\Sudhaus7Base\PostProcess\MailPostProcessor::class,
    ];
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\SUDHAUS7\Sudhaus7Base\Backend\Tce\Formevals\EmbedurlEvaluation::class] = '';

/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
$signalSlotDispatcher->connect(
	\SUDHAUS7\Sudhaus7Base\Backend\Tce\Formevals\EmbedurlEvaluation::class,  // Signal class name
	'evaluateFieldValue',                                  // Signal name
	\SUDHAUS7\Sudhaus7Base\Parser\Embedurl\Youtube::class,        // Slot class name
	'signal'                               // Slot name
);
$signalSlotDispatcher->connect(
	\SUDHAUS7\Sudhaus7Base\Backend\Tce\Formevals\EmbedurlEvaluation::class,  // Signal class name
	'evaluateFieldValue',                                  // Signal name
	\SUDHAUS7\Sudhaus7Base\Parser\Embedurl\Vimeo::class,        // Slot class name
	'signal'                               // Slot name
);

$defaultConfig = include( \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath( $_EXTKEY ) . '/Configuration/DefaultConfiguration.php' );
$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive( $defaultConfig , $GLOBALS['TYPO3_CONF_VARS']);

