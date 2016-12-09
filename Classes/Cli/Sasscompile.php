<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 09/12/2016
 * Time: 12:23
 */

namespace SUDHAUS7\Sudhaus7Base\Cli;


use SUDHAUS7\Sudhaus7Base\Compiler\Sass;

class Sasscompile extends \TYPO3\CMS\Core\Controller\CommandLineController
{

    public $cli_help = array(
        'name' => 'Sass Compiler',
        'synopsis' => '###OPTIONS###',
        'description' => 'Compiles a sass/scss File or tree',
        'examples' => 'cli_dispatch.phpsh sasscompiler -source project.sccs --target output.css',
        'options' => '',
        'license' => 'GNU GPL - free software!',
        'author' => 'Frank Berger'
    );


    function __construct()
    {

        $this->db = $GLOBALS['TYPO3_DB'];
        $this->cli_options[] = array(
            '-h',
            'This help',
        );

        $this->cli_options[] = array(
            '-source filename',
            'The Full Path to the Sourcefile',
        );

        $this->cli_options[] = array(
            '-target filename',
            'The Full Path to the Target file',
        );
        $this->cli_options[] = array(
            '-minified',
            'Output should be minified',
        );
        $this->cli_options[] = array(
            '-inlined',
            'url() Sources should be inlined',
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
        if (!isset($this->cli_args['-source']) || !isset($this->cli_args['-target'])) {
            $this->cli_help();
            exit;
        }

        $compiler = new Sass($this->cli_args['-source'][0]);
        if (isset($this->cli_args['-minified'])) $compiler->minified = true;
        $compiler->compile();
        if (isset($this->cli_args['-inlined'])) $compiler->inlineUrls();
        if ($compiler->isValid()) {
            $compiler->toFile($this->cli_args['-target'][0]);
            $a = $compiler->getOutput();
            foreach ($a as $l) $this->cli_echo($l);
        } else {
            $a = $compiler->getOutput();
            foreach ($a as $l) $this->cli_echo($l,true);
        }

    }
}
