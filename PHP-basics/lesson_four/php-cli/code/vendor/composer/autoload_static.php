<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9cb54e4abc1125a52d0fd312a87103cc
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\Oop\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\Oop\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9cb54e4abc1125a52d0fd312a87103cc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9cb54e4abc1125a52d0fd312a87103cc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9cb54e4abc1125a52d0fd312a87103cc::$classMap;

        }, null, ClassLoader::class);
    }
}
