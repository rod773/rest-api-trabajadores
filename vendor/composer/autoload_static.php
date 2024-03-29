<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdfdd4b855d28f58239d99a37314e3883
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdfdd4b855d28f58239d99a37314e3883::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdfdd4b855d28f58239d99a37314e3883::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdfdd4b855d28f58239d99a37314e3883::$classMap;

        }, null, ClassLoader::class);
    }
}
