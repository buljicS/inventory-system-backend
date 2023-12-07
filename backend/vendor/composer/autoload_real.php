<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitcabbf6c5067ac6a2b3a253b899a851f2
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitcabbf6c5067ac6a2b3a253b899a851f2', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitcabbf6c5067ac6a2b3a253b899a851f2', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitcabbf6c5067ac6a2b3a253b899a851f2::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
