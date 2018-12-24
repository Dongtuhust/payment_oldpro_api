<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit54f8f6fd8c965f373ac0b2e0df3abd47
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpAmqpLib\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpAmqpLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-amqplib/php-amqplib/PhpAmqpLib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit54f8f6fd8c965f373ac0b2e0df3abd47::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit54f8f6fd8c965f373ac0b2e0df3abd47::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
