<?php

/**
 * Model.php - dioxid
 * @author Andre 'Necrotex' Peiffer <necrotex@gmail.com>
 * @version 1.0
 * @package model
 */

namespace  dioxid\model;

use dioxid\error\exception\EngineNotFoundException;

use Exception;
use dioxid\config\Config;
use dioxid\model\query\Query;

class Model {

	protected static $_engine;

	protected static $_name=false;
	protected static $_driver=false;

	final public function __construct() {

		if(!static::$_driver){
			$class = 'dioxid\\model\\engine\\'. Config::getVal('database', 'driver', true) . 'Engine';
		} else {
			$class = 'dioxid\\model\\engine\\'. static::$_driver . 'Engine';
		}

		if(class_exists($class)){
			static::$_engine = call_user_func_array(array($class, 'getInstance'), array(static::$_name));
		}
		else {
			throw new EngineNotFoundException($class . ' not found');
		}

		static::_init();
	}

	public static function _init(){}

	public function __call($method, $args){
		if(!method_exists(static::$_engine, $method))
			throw new Exception();
		call_user_func_array(array(static::$_engine, $method), $args);
	}

	protected static function query(){
		return new Query(static::$_name, &static::$_engine);
	}

}

?>