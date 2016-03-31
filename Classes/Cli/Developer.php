<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 20/05/14
 * Time: 14:12
 */
namespace SUDHAUS7\Sudhaus7Base\Cli;

use TYPO3\CMS\Core\Utility\GeneralUtility;

if (!defined('TYPO3_cliMode')) die('You cannot run this script directly!');


/**
 *
 * @package Zwbisdrei\ZwbisdreiCatalog\Cli
 */
class Developer extends \TYPO3\CMS\Core\Controller\CommandLineController {

    public $cli_help = array(
        'name' => 'Developer',
        'synopsis' => '###OPTIONS###',
        'description' => 'Create Model ',
        'examples' => 'cli_dispatch.phpsh s7_developer -table tx_bfactorbkv4_config -ns BFACTOR\\BfactorBkv4 -class Config',
        'options' => '',
        'license' => 'GNU GPL - free software!',
        'author' => 'Frank Berger'
    );
    private $extbase = [];
    private $declaredclasses = [];

    function __construct() {

        $this->db = $GLOBALS['TYPO3_DB'];
        $this->cli_options[] = array(
            '-h',
            'This help',
        );

        $this->cli_options[] = array(
            '-table tablename',
            'run for table',
        );

        $this->cli_options[] = array(
            '-class Classname',
            'Generate this Class name',
        );
        $this->cli_options[] = array(
            '-ns Namespace',
            'Base Namespace without Domain\\Model, for example BFACTOR\\BfactorBkv4',
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
        if (!isset($this->cli_args['-table']) || !isset($this->cli_args['-class']) || !isset($this->cli_args['-ns'])) {
            $this->cli_help();
            exit;
        }
        $verbose = isset($this->cli_args['-v']);

        $table = $this->cli_args['-table'][0];
        $class = $this->cli_args['-class'][0];
        $ns = $this->cli_args['-ns'][0];

        $aNS = explode('\\',$ns);
        $extention = 'tx_'.strtolower($aNS[1]);

        $ext = GeneralUtility::camelCaseToLowerCaseUnderscored($aNS[1]);

        $extbasebase = file_get_contents(PATH_site.'/typo3/sysext/extbase/ext_typoscript_setup.txt');
        $this->extbase = \SUDHAUS7\Sudhaus7Base\Tools\Typoscript::parse($extbasebase);

        $this->declaredclasses = get_declared_classes();

        //print_r(array($table,$class,$ns,$extention,$ext));
        @mkdir(PATH_site.'/typo3conf/ext/'.$ext.'/Classes');
        @mkdir(PATH_site.'/typo3conf/ext/'.$ext.'/Classes/Domain');
        @mkdir(PATH_site.'/typo3conf/ext/'.$ext.'/Classes/Domain/Model');
        @mkdir(PATH_site.'/typo3conf/ext/'.$ext.'/Classes/Domain/Repository');


        $repo = '<'.'?'.'php
namespace '.$ns.'\\Domain\\Repository;

/**
 * Class '.$class.'Repository
 *
 * @package '.$ns.'\\Domain\\Repository
 */
class '.$class.'Repository extends \TYPO3\CMS\Extbase\Persistence\Repository {

}
';
        file_put_contents(PATH_site.'/typo3conf/ext/'.$ext.'/Classes/Domain/Repository/'.$class.'Repository.php',$repo);

        $code = '';
        foreach ($GLOBALS['TCA'][$table]['columns'] as $field => $config) {
          //  print_r(array($field,$config));

            switch ($config['config']['type']) {
                case 'select':
                    $mytype = '';
                    if (isset($config['config']['foreign_table'])) {
                        if ($mytype = $this->findClass($config['config']['foreign_table'])) {
                            $mytype = '\\TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage<\\'.$mytype.'>';
                        } else {
                            $test = $extention . '_domain_model_';
                            if (substr($config['config']['foreign_table'], 0, strlen($test)) == $test) {
                                //tx_bfactornewspage_domain_model_tag
                                $table = $config['config']['foreign_table'];
                                if (substr($table, 0, 3) == 'tx_') $table = substr($table, 3);
                                $tmp = explode('_', $table);
                                $cls = [];
                                foreach ($tmp as $v) $cls[] = ucfirst($v);
                                $clstxt = implode('//', $cls);
                                foreach ($this->declaredclasses as $declaredclass) {
                                    if (strrpos($declaredclass, $clstxt) > 0) {
                                        //$mytype = '\\'.$declaredclass;
                                        $tst = explode('\\', $declaredclass);
                                        $obj = array_pop($tst);
                                        array_pop($tst);
                                        $tst[] = 'Repository';
                                        $tst[] = $obj . 'Repository';
                                        $mytype = '\\' . implode('\\', $tst);
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($mytype)) {
                        $code .= $this->makeGetterSetter($field, $mytype, true);
                    } else {
                        $code .= $this->makeGetterSetter($field, 'string');
                    }
                    break;
                case 'inline':
                    $mytype = '';
                    if (isset($config['config']['foreign_table'])) {
                        if ($mytype = $this->findClass($config['config']['foreign_table'])) {
                            $mytype = '\\TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage<\\'.$mytype.'>';
                        }

                    } else {
                        $test = $extention.'_domain_model_';
                        if (substr($config['config']['foreign_table'],0,strlen($test))==$test) {
                            //tx_bfactornewspage_domain_model_tag
                            $table = $config['config']['foreign_table'];
                            if (substr($table,0,3) =='tx_') $table = substr($table,3);
                            $tmp = explode('_',$table);
                            $cls = [];
                            foreach ($tmp as $v) $cls[] = ucfirst($v);
                            $clstxt = implode('//',$cls);
                            foreach($this->declaredclasses as $declaredclass) {
                                if (strrpos($declaredclass,$clstxt) > 0) {
                                    //$mytype = '\\'.$declaredclass;
                                    $tst = explode('\\',$declaredclass);
                                    $obj = array_pop($tst);
                                    array_pop($tst);
                                    $tst[]='Repository';
                                    $tst[]=$obj.'Repository';
                                    $mytype = '\\'.implode('\\',$tst);
                                }
                            }
                        }

                    }
                    if (!empty($mytype)) {
                        $code .= $this->makeGetterSetter($field, $mytype, true);
                    } else {
                        $code .= $this->makeGetterSetter($field, 'int');
                    }
                    break;
                case 'text':
                    $code .= $this->makeGetterSetter($field,'string');
                    break;
                case 'input':

                    $mytype = 'string';
                    if (isset($config['config']['eval'])) {
                        switch($config['config']['eval']) {
                            case 'int':
                                $mytype = 'int';
                                break;
                            case 'date':
                                $mytype = '\\DateTime';
                                break;
                            case 'datetime':
                                $mytype = '\\DateTime';
                                break;
                            default:
                                $mytype = 'string';
                                break;
                        }
                    }

                    $code .= $this->makeGetterSetter($field,$mytype);
                    break;
                case 'check':
                    $code .= $this->makeGetterSetter($field,'bool');
                    break;
                default:
                    $code .= $this->makeGetterSetter($field,'string');
            }
        }

        $cls = '<'.'?'.'php
namespace '.$ns.'\\Domain\\Model;

/**
 * Model '.$class.'
 * @package '.$ns.'\\Domain\\Model
 */
class '.$class.' extends \\TYPO3\\CMS\\Extbase\\DomainObject\\AbstractEntity {

'.$code.'

}
';
        file_put_contents(PATH_site.'/typo3conf/ext/'.$ext.'/Classes/Domain/Model/'.$class.'.php',$cls);


    }

    private function findClass($table)
    {
        $foundclass = false;
        foreach ($this->extbase['config.']['tx_extbase.']['persistence.']['classes.'] as $class => $config) {
            if ($config['mapping.']['tableName'] == $table) {
                $foundclass = substr($class, 0, -1);
            }
        }
        return $foundclass;
    }

    private function makeGetterSetter($field,$type,$inject=false) {
        if ($inject) {
            $s = '
    /**
     * @var '.$type.'
     * @inject
     */
    protected $'.$field.';
';

        } else {


            $s = '
    /**
     * @var '.$type.'
     */
    protected $'.$field.';
';
        }
        $s .= '
    /**
     * '.GeneralUtility::underscoredToUpperCamelCase($field).'
     *
     * @return '.$type.' '.$field.'
     */
     public function get'.GeneralUtility::underscoredToUpperCamelCase($field).'() {
        return $this->'.$field.';
     }

    /**
     * '.GeneralUtility::underscoredToUpperCamelCase($field).'
     *
     * @param '.$type.' $'.$field.'
     * @return $this
     */
     public function set'.GeneralUtility::underscoredToUpperCamelCase($field).'($'.$field.') {
        $this->'.$field.' = $'.$field.';
        return $this;
     }
';
        return $s;
    }
}
