<?php

namespace Homework3\_Framework;


class EntityHandler
{

    public const ENTITY_FOLDER = 'Entity';

    public function __construct()
    {
        foreach (glob('../src/Entity/*.php') as $file)
        {
            require_once $file;

            // get the file name of the current file without the extension
            // which is essentially the class name
            $class = basename($file, '.php');

            if (class_exists($class))
            {
                $obj = new $class;
                $obj->OnCall();
            }
        }

        $classes = get_declared_classes();
        $implementsInterface = array();
        foreach($classes as $class) {
            try {
                echo $class;
                $reflect = new \ReflectionClass($class);
                if ($reflect->implementsInterface(FrameworkEntity::class))
                    $implementsInterface[] = $class;
            } catch (\ReflectionException $e) {
                $exception = new ExceptionHandler();
                $exception->handle('Reflection problem.');
            }
        }
    }

}