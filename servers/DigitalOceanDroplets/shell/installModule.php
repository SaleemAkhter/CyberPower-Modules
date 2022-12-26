<?php

define('DS', DIRECTORY_SEPARATOR);
$modulePath = dirname(__DIR__);
$whmcsPath  = substr(__DIR__, 0, strpos(__DIR__, 'modules' . DS . 'servers' . DS));

require_once __DIR__ . DS . 'ProgressBar' . DS . 'Progress.php';
$readData = $argv ? $argv : $_SERVER['argv'];

$moduleName = null;
$version    = '1.0.0';


foreach ($readData as $key => $string)
{
    if ('--moduleName' === $string)
    {
        $moduleName = $readData[$key + 1];
    }
    elseif ('--version' === $string)
    {
        $version = $readData[$key + 1];
    }
}

if ($moduleName === null || str_replace(' ', '', $moduleName) === '')
{
    print '\n\033[31mError: Please provide a module name, eg. "ModuleFramework"\n\033[0m';
    die();
}

if (strpos($moduleName, ' ') !== false)
{
    print '\n\033[31mError: There can\'t be a whitecase in moduleName. "' . $moduleName . '"\n\033[0m';
    die();
}


$dirs = [
            'app',
            'core',
            'cron',
            'shell'
];

echo "\n\033[32m Install module files.\n\n\033[0m";
$endFullPath = DS . '...';
foreach ($dirs as $dir)
{
    $fullPath  = $modulePath . DS . $dir;
    $Directory = new RecursiveDirectoryIterator($fullPath, RecursiveDirectoryIterator::KEY_AS_FILENAME | RecursiveDirectoryIterator::CURRENT_AS_FILEINFO);
    $Iterator  = new RecursiveIteratorIterator($Directory);
    $Regex     = new RegexIterator($Iterator, '/\.php$/i', RegexIterator::MATCH, RegexIterator::USE_KEY);

    echo "\033[35m Path: '{$fullPath}{$endFullPath}'.\n\033[0m";

    $count    = count($Regex);
    $number   = 0;
    $progress = new SimpleProgress();


    foreach ($Regex as $file)
    {
        $path    = $file->getPathname();
        $content = file_get_contents($path);
        $content = str_replace("ModuleFramework", $moduleName, $content);
        file_put_contents($path, $content);

        $number = $number + 1;
        $progress->update($number, $count);
    }
    unset($progress);
}

echo "\n\033[32m Install configuration.\n\033[0m";


foreach (['app' . DS . 'Config', 'core' . DS . 'Config'] as $dir)
{
    $fullPath  = $modulePath . DS . $dir;
    $Directory = new RecursiveDirectoryIterator($fullPath, RecursiveDirectoryIterator::KEY_AS_FILENAME | RecursiveDirectoryIterator::CURRENT_AS_FILEINFO);
    $Iterator  = new RecursiveIteratorIterator($Directory);
    $Regex     = new RegexIterator($Iterator, '/\.yml$/i', RegexIterator::MATCH, RegexIterator::USE_KEY);

    echo "\033[35m Path: '{$fullPath}{$endFullPath}'.\n\033[0m";

    $count    = count($Regex);
    $number   = 0;
    $progress = new SimpleProgress();


    foreach ($Regex as $file)
    {
        $path    = $file->getPathname();
        $content = file_get_contents($path);
        $content = str_replace("ModuleFramework", $moduleName, $content);
        $content = str_replace("1.1.0", $version, $content);
        file_put_contents($path, $content);

        $number = $number + 1;
        $progress->update($number, $count);
    }
    unset($progress);
}

foreach (['hooks.php', 'ModuleFramework.php', 'composer.json'] as $file)
{
    $content = file_get_contents($modulePath . DS . $file);
    $content = str_replace("ModuleFramework", $moduleName, $content);
    file_put_contents($modulePath . DS . $file, $content);
}

rename($modulePath . DS . 'ModuleFramework.php', $modulePath . DS . $moduleName . '.php');
rename($modulePath, dirname($modulePath) . DS . $moduleName);

echo "\n\033[32m Successfully installed files for the module.\n\033[0m";
