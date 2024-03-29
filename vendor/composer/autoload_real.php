<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitb7d127571c20a8caf2aa8ce6683ec242
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

        spl_autoload_register(array('ComposerAutoloaderInitb7d127571c20a8caf2aa8ce6683ec242', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitb7d127571c20a8caf2aa8ce6683ec242', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitb7d127571c20a8caf2aa8ce6683ec242::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
