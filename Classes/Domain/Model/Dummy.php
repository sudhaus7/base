<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 20/12/2016
 * Time: 17:04
 */

namespace SUDHAUS7\Sudhaus7Base\Domain\Model;


class Dummy
{
    private $data = array();
    public function __call($method,$arguments) {
        $call = substr($method,0,3);
        $var = strtolower(substr($method,3));
        if ($call=='get') {
            if (isset($this->data[$var])) return $this->data[$var];
            return null;
        }
        if ($call == 'set') {
            $this->data[$var] = $arguments[0];
        }
        return false;
    }
    public function __construct($data = array())
    {
        $this->data = $data;
    }
}
