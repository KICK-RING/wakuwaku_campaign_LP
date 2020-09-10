<?php

class Singleton
{
    private static $_instance = [];

    final private function __construct()
    {
        if (isset(self::$_instance[get_called_class()])) {
            throw new Exception('インスタンス済み');
        }
        static::initialize();
    }

    protected function initialize()
    {
    }

    final public static function getInstance()
    {
        $class = get_called_class();

        if (!isset(self::$_instance[$class])) {
            self::$_instance[$class] = new static();
        }

        return self::$_instance[$class];
    }

    final private function __clone()
    {
        throw new Exception('cloneできません');
    }

}
