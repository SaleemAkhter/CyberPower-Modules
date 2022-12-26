<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit066cc772638e1bbd7a4ee6ad45d9d72a
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '32dcc8afd4335739640db7d200c1971d' => __DIR__ . '/..' . '/symfony/polyfill-apcu/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'd767e4fc2dc52fe66584ab8c6684783e' => __DIR__ . '/..' . '/adbario/php-dot-notation/src/helpers.php',
        '728cd66d334b33c0fb1ed0fe1060a82b' => __DIR__ . '/..' . '/rappasoft/laravel-helpers/src/helpers.php',
        'd86f44a59ed237b8290c4c5b244dbd02' => __DIR__ . '/../..' . '/core/Helper/Functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Polyfill\\Apcu\\' => 22,
            'Symfony\\Component\\Yaml\\' => 23,
            'Symfony\\Component\\HttpFoundation\\' => 33,
            'Symfony\\Component\\DependencyInjection\\' => 38,
            'Symfony\\Component\\Cache\\' => 24,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Log\\' => 8,
            'Psr\\Container\\' => 14,
            'Psr\\Cache\\' => 10,
            'Piwik\\Ini\\' => 10,
        ),
        'M' => 
        array (
            'Mso\\IdnaConvert\\' => 16,
            'ModulesGarden\\Servers\\VultrVps\\Packages\\' => 40,
            'ModulesGarden\\Servers\\VultrVps\\Core\\' => 36,
            'ModulesGarden\\Servers\\VultrVps\\App\\' => 35,
        ),
        'A' => 
        array (
            'Adbar\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Polyfill\\Apcu\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-apcu',
        ),
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
        'Symfony\\Component\\HttpFoundation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/http-foundation',
        ),
        'Symfony\\Component\\DependencyInjection\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/dependency-injection',
        ),
        'Symfony\\Component\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/cache',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'Piwik\\Ini\\' => 
        array (
            0 => __DIR__ . '/..' . '/piwik/ini/src',
        ),
        'Mso\\IdnaConvert\\' => 
        array (
            0 => __DIR__ . '/..' . '/mso/idna-convert/src',
        ),
        'ModulesGarden\\Servers\\VultrVps\\Packages\\' => 
        array (
            0 => __DIR__ . '/../..' . '/packages',
        ),
        'ModulesGarden\\Servers\\VultrVps\\Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
        'ModulesGarden\\Servers\\VultrVps\\App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'Adbar\\' => 
        array (
            0 => __DIR__ . '/..' . '/adbario/php-dot-notation/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'J' => 
        array (
            'JsonMapper' => 
            array (
                0 => __DIR__ . '/..' . '/netresearch/jsonmapper/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit066cc772638e1bbd7a4ee6ad45d9d72a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit066cc772638e1bbd7a4ee6ad45d9d72a::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit066cc772638e1bbd7a4ee6ad45d9d72a::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit066cc772638e1bbd7a4ee6ad45d9d72a::$classMap;

        }, null, ClassLoader::class);
    }
}
