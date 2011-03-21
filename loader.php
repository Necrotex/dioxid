<?php
namespace dioxid;

class Loader {
    protected static $setup;

    public static function setup($setup = array()){
        static::$setup[__NAMESPACE__] = __DIR__;
        static::$setup = array_merge(static::$setup, $setup);
    }

    public static function load($namespace) {
	    $chunks = explode('\\', $namespace);

	    if(key_exists($chunks[0], static::$setup)){
            $path = static::$setup[$chunks[0]] . '/' . str_replace('\\', '/', substr($namespace, strlen($chunks[0]) + 1)) . '.php';

            try {
                require_once($path);
            } catch (Exception $e) {
                return false;
            }

	    } else {
            return false;
	    }
 	    return true;
    }

    public static function register(){
        spl_autoload_register(__NAMESPACE__ . '\Loader::load');
    }
}

?>