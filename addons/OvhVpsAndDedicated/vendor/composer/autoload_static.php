<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8a8d2e44c42d2e753c586dce93f2ed2a
{
    public static $files = array (
        '32dcc8afd4335739640db7d200c1971d' => __DIR__ . '/..' . '/symfony/polyfill-apcu/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '023d27dca8066ef29e6739335ea73bad' => __DIR__ . '/..' . '/symfony/polyfill-php70/bootstrap.php',
        'd767e4fc2dc52fe66584ab8c6684783e' => __DIR__ . '/..' . '/adbario/php-dot-notation/src/helpers.php',
        '728cd66d334b33c0fb1ed0fe1060a82b' => __DIR__ . '/..' . '/rappasoft/laravel-helpers/src/helpers.php',
        '6d79ce3bf0ae6586a08259a610e296cb' => __DIR__ . '/../..' . '/core/Helper/Functions.php',
        '97fdc1c2b34bfb2fe1f42bcb2495d946' => __DIR__ . '/../..' . '/../../servers/OvhVpsAndDedicated/core/Bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Php70\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
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
            'ModulesGarden\\OvhVpsAndDedicated\\Core\\' => 38,
            'ModulesGarden\\OvhVpsAndDedicated\\App\\' => 37,
        ),
        'A' => 
        array (
            'Adbar\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Php70\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php70',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
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
        'ModulesGarden\\OvhVpsAndDedicated\\Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
        'ModulesGarden\\OvhVpsAndDedicated\\App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'Adbar\\' => 
        array (
            0 => __DIR__ . '/..' . '/adbario/php-dot-notation/src',
        ),
    );

    public static $classMap = array (
        'ArithmeticError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/ArithmeticError.php',
        'AssertionError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/AssertionError.php',
        'DivisionByZeroError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/DivisionByZeroError.php',
        'Error' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/Error.php',
        'ParseError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/ParseError.php',
        'SessionUpdateTimestampHandlerInterface' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/SessionUpdateTimestampHandlerInterface.php',
        'TypeError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/TypeError.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8a8d2e44c42d2e753c586dce93f2ed2a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8a8d2e44c42d2e753c586dce93f2ed2a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8a8d2e44c42d2e753c586dce93f2ed2a::$classMap;

        }, null, ClassLoader::class);
    }
}
