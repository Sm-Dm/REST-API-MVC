 <?php

 trait Singleton 
 {
    private static $instance = null;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    public static function getInstance()
    {
		return 
			self::$instance===null
				? self::$instance = new static()
				: self::$instance;
    }
}