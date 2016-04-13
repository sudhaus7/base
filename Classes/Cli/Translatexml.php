<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 12/04/16
 * Time: 17:58
 */

namespace SUDHAUS7\Sudhaus7Base\Cli;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class Translatexml extends \TYPO3\CMS\Core\Controller\CommandLineController
{

    public $cli_help = array(
        'name' => 'Translatexml',
        'synopsis' => '###OPTIONS###',
        'description' => 'Translate Classic XML ',
        'examples' => 'cli_dispatch.phpsh s7_translatexml -in INFILE -out OUTFILE',
        'options' => '',
        'license' => 'GNU GPL - free software!',
        'author' => 'Frank Berger'
    );
    private $extbase = [];
    private $declaredclasses = [];

    function __construct()
    {

        $this->db = $GLOBALS['TYPO3_DB'];
        $this->cli_options[] = array(
            '-h',
            'This help',
        );

        $this->cli_options[] = array(
            '-in INFILE',
            'read this file',
        );

        $this->cli_options[] = array(
            '-out OUTFILE',
            'write this file',
        );

        $this->cli_options[] = array(
            '-v',
            'verbose output'
        );
        parent::__construct();

    }

    function cli_main($argv)
    {
        $this->cli_setArguments($argv);


        $this->cli_validateArgs();
        if (isset($this->cli_args['-h'])) {
            $this->cli_help();
            exit;
        }
        if (!isset($this->cli_args['-in']) || !isset($this->cli_args['-out'])) {
            $this->cli_help();
            exit;
        }

        $infile = GeneralUtility::getFileAbsFileName($this->cli_args['-in'][0]);
        $outfile = GeneralUtility::getFileAbsFileName($this->cli_args['-out'][0]);

        if (!is_file($outfile)) {
            file_put_contents($outfile, '<' . '?' . 'xml version="1.0" encoding="utf-8" standalone="yes" ' . '?' . '>
<xliff version="1.0">
    <file source-language="en" datatype="plaintext" original="messages" date="2014-09-12T13:49:12Z">
        <header>
            <generator>LFEditor</generator>
        </header>
        <body>
        </body>
    </file>
</xliff>');
        }

        $in = simplexml_load_file($infile);
        $out = simplexml_load_file($outfile);
        foreach ($in->data as $languageKey) {
            foreach ($languageKey->languageKey->label as $e) {

                $key = (string)$e['index'];
                $test = $out->xpath('//file/body/trans-unit[@id="' . $key . '"]');
                if (empty($test)) {
                    $value = (string)$e;
                    //print_r(array($key,$value));
                    $elem = $out->file->body->addChild('trans-unit');
                    $elem->addAttribute('id', $key);
                    $elem->addAttribute('xml:space', 'preserve');
                    $elem->addChild('source', $value);
                }
            }
        }
        $xml = $out->asXml();
        $xml = str_replace(' space', ' xml:space', $xml);
        //  $xml = str_replace('</source>',"</source>\n",$xml);
        $xml = str_replace('</trans-unit>', "</trans-unit>\n", $xml);

        $xml = str_replace("\n\n", "\n", $xml);
        file_put_contents($outfile, $xml);

    }
}
