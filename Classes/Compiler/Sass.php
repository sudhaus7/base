<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 09/12/2016
 * Time: 11:37
 */

namespace SUDHAUS7\Sudhaus7Base\Compiler;


use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;

class Sass
{
    private $config;
    private $output = [];
    private $parameter = '';
    public $minified = false;
    private $src;
    private $success = false;
    private $buffer = '';
    /**
     * Sass constructor.
     * @param $srcfile
     * @throws FileDoesNotExistException
     */
    public function __construct($srcfile)
    {
        $this->config  = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sudhaus7_base']);
        $this->src = $srcfile;
        if (!is_file($srcfile)) throw new FileDoesNotExistException('SCSS File '.$srcfile);

    }

    /**
     * @param $tgt filename
     * @return int
     */
    public function toFile($tgt) {
        if (empty($this->buffer)) $this->compile();
        $this->output[] = 'Wrote Buffer to '.$tgt;
        return file_put_contents($tgt, $this->buffer);
    }

    /**
     * @return string
     */
    public function toString() {
        if (empty($this->buffer)) $this->compile();
        $this->output[] = 'Returned Buffer to Caller';
        return $this->buffer;
    }

    /**
     * @return bool
     */
    public function compile() {
        $a = array();
        $tmpdir = sys_get_temp_dir();
        $tgt = uniqid('sudhaus7-base-sass-compile-',true).'.css';
        $cmd = $this->config['NODE'] . ' ' . $this->config['SASS'].' ';
        if ($this->minified) $cmd .=  ' --output-style compressed ';
        $cmd .=  $this->src . ' ' . $tmpdir .'/'. $tgt . ' 2>&1';
        $ret = 0;
        $this->output[]='Executing Command '.$cmd;
        exec($cmd, $this->output, $ret);
        if ((int)$ret > 0) {
            return false;
        }
        $this->success = true;
        $this->buffer = file_get_contents($tmpdir .'/'. $tgt);
        @unlink($tmpdir .'/'. $tgt);
        return true;
    }

    /**
     * @return array debug output
     */
    public function getOutput() {
        return $this->output;
    }

    /**
     * @return bool
     */
    public function isValid() {
        return $this->success;
    }

    public function inlineUrls() {
        if ($this->success && !empty($this->buffer)) {
            $buf = $this->buffer;

            preg_match_all('/url\("(\/typo3conf\/.*)"\)/Ui', $buf, $m);
            foreach ($m[0] as $k => $v) {
                $file = PATH_site . $m[1][$k];

                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $mimetype = null;
                switch ($ext) {
                    case 'png':
                        $mimetype = 'image/png';
                        break;
                    case 'jpg':
                        $mimetype = 'image/jpeg';
                        break;
                    case 'jpeg':
                        $mimetype = 'image/jpeg';
                        break;
                    case 'gif':
                        $mimetype = 'image/gif';
                        break;
                    case 'svg':
                        $mimetype = 'image/svg+xml';
                        break;
                    case 'eot':
                        $mimetype = 'application/vnd.ms-fontobject';
                        break;
                    case 'ttf':
                        $mimetype = 'font/truetype';
                        break;
                    case 'woff2':
                        $mimetype = 'font/woff2';
                        break;
                    case 'woff':
                        $mimetype = 'application/font-woff';
                        break;
                    case 'off':
                        $mimetype = 'font/openfont';
                        break;
                    default:
                        break;
                }
                if ($mimetype) {
                    if (filesize($file) < $this->config['MAXINLINE']) {
                        $this->output[] = 'Inlined File '.$file;
                        $data = file_get_contents($file);
                        $file = '"data:' . $mimetype . ';base64,' . base64_encode($data) . '"';
                        $buf = str_replace($v, 'url(' . $file . ')', $buf);

                    }
                }
            }
            $this->buffer = $buf;

        }
    }

}
