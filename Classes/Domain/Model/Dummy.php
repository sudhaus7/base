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
	protected $data = [];
	public function __construct($data) {
		$this->data = $data;
	}

	public function __get($field) {
		if (isset($this->data[$field])) return $this->data[$field];
		return null;
	}
	public function __set($field,$value) {
		$this->data[$field]=$value;
		return $this;
	}
	public function __call( $name, $arguments ) {
		$direction = \substr($name,0,3);
		$var = self::from_camel_case(\substr($name,3));

		if ($direction == 'set') {
			$this->data[$var]=$arguments;
		}
		if ($direction == 'get') {
			if (isset($this->data[$var])) {
				return $this->data[$var];
			}
			return null;
		}
		return $this;
	}

	private static function from_camel_case($input) {
		return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $input)), '_');
	}
	private static function to_camel_case($input) {
		return preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $input);
	}
}
