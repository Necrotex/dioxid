<?php

/**
 * View.php - dioxid
 * @author Andre 'Necrotex' Peiffer <necrotex@gmail.com>
 * @version 1.0
 * @package view
 */

namespace dioxid\view;

use dioxid\error\exception\ClassNotFoundException;

use dioxid\Loader;

use dioxid\lib\Base;

use dioxid\exception\TemplateNotFoundException;

use dioxid\config\Config;
use dioxid\error\exception\EngineNotFoundException;
use dioxid\error\exception\MethodNotFoundException;

class View extends Base {
	public static $engine;

	public static function _init(){

		try{
			static::setEngine(Config::getVal('view', 'engine'));
		}
		catch (EngineNotFoundException $e){

			static::$engine = false;
		}
	}

	public static function setEngine($engine){
		$engine_class = __NAMESPACE__ . '\\engine\\' . ucfirst($engine) . 'Engine';

		if(class_exists($engine_class)) {
			static::$engine = $engine_class::getInstance();

		} else {
			throw new EngineNotFoundException();
		}
	}

	public static function __callstatic($name, $args = array()){
		if(!static::$engine)
			throw new EngineNotFoundException();

		if(method_exists(__CLASS__, $name)){
			call_user_func_array(array(__CLASS__, $name), $args);
		}
		elseif(method_exists(static::$engine, $name)){
			call_user_func_array(array(static::$engine, $name), $args);
		}
		else {
			throw new MethodNotFoundException();
		}
	}

	public function __call($name, $args= array()){
		if(!static::$engine)
			throw new EngineNotFoundException();

		if(method_exists(__CLASS__, $name)){
			call_user_func_array(array(__CLASS__, $name), $args);
		}
		elseif(method_exists(static::$engine, $name)){
			call_user_func_array(array(static::$engine, $name), $args);
		}
		else {
			throw new MethodNotFoundException();
		}
	}

	public function __set($k,$v){
		return call_user_func_array(array(static::$engine, '__set'), array($k,$v));
	}

	public function __get($key){
		return call_user_func_array(array(static::$engine, '__get'), array($key));
	}


	public static function useHelper($name) {
		if (($pos = strripos($name, 'Helper')) !== false) {
            $name = substr($name, 0, $pos);
        }

		$class = __NAMESPACE__ . '\\helper\\' . ucfirst($name) . 'Helper';

		if(!class_exists($class))
			throw new ClassNotFoundException("Helper $name not found");

		static::$engine->assign('_'. $name, $class::getInstance());
	}

	public function __destruct() {
		if(static::$engine)
			call_user_func(array(static::$engine, 'finally'));
	}
}

?>