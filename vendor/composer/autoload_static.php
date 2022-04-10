<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7d6b30a2d5f49b21920b097c88ca3be2
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Parsy\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Parsy\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit7d6b30a2d5f49b21920b097c88ca3be2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7d6b30a2d5f49b21920b097c88ca3be2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7d6b30a2d5f49b21920b097c88ca3be2::$classMap;

        }, null, ClassLoader::class);
    }
}