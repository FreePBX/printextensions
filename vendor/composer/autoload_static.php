<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite1bdd39b90ac7ca34b3a23cc0621e1ac
{
    public static $classMap = array (
        'FPDF' => __DIR__ . '/..' . '/setasign/fpdf/fpdf.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInite1bdd39b90ac7ca34b3a23cc0621e1ac::$classMap;

        }, null, ClassLoader::class);
    }
}
