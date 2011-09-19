<?php

/**
 * Loader.php - dioxid
 * @author Andre 'Necrotex' Peiffer <necrotex@gmail.com>
 * @version 1.0
 * @package Common
 */

namespace dioxid\common;
use dioxid\common\exception\NotFoundException;

use Exception;

/**
 * dioxid$Loader
 * Classloader for Dioxid based on Namespaceloading.
 * @author Andre 'Necrotex' Peiffer <necrotex@gmail.com>
 */

class Loader {
	/**
	 * Namespaces and their basedirecoties
	 * @var unknown_type
	 */
    protected static $setup = array();

    /**
     * Method: setup
     * add namespaces and basedirectories to the loader
     * @param unknown_type $setup
     */
    public static function setup($setup = array()){
    	if(count(static::$setup) != 0)
    		throw new Exception('You cant use Loader::setup twice!!');

    	//Add Dioxid as Standardnamespace
    	static::$setup['dioxid'] = dirname(__DIR__);
        static::$setup = array_merge(static::$setup, $setup);
    }

    /**
     * Method: load
     * This loads the actual files.
     * Not intent for direct use. This should get registered as autoloader
     * @param string $namespace
     */
    public static function load($namespace) {
	    $chunks = explode('\\', $namespace);

	    if(key_exists($chunks[0], static::$setup)){
            $path = static::$setup[$chunks[0]] . DIRECTORY_SEPARATOR .
            	str_replace('\\', DIRECTORY_SEPARATOR,
            	substr($namespace, strlen($chunks[0]) + 1)) . '.php';

            #if(!file_exists($path))
            #	throw new NotFoundException('Class "'.$namespace.'" not found.');

            try {
                require_once($path);
            } catch (Exception $e) {
                throw new Exception('Loader: Class "'.$namespace.'" can\'t be loaded');
            }
	    } else {
            return false;
	    }
 	    return true;
    }

	/**
	 * Method: register
	 * Registers the Loader to autoload register.
	 */
    public static function register(){
        spl_autoload_register(__NAMESPACE__ . '\Loader::load');
    }
}

?>
