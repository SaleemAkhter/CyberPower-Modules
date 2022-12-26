<?php

define('DS', DIRECTORY_SEPARATOR);
$modulePath = dirname(__DIR__);
$whmcsPath  = substr(__DIR__, 0, strpos(__DIR__, 'modules' . DS . 'servers' . DS));


require_once $whmcsPath . DS . 'init.php';
require_once $modulePath . DS . 'core' . DS . 'Bootstrap.php';
require_once __DIR__ . DS . 'ProgressBar' . DS . 'Progress.php';

$dirs = [
    'app',
    'core'
];

\ModulesGarden\Servers\HetznerVps\Core\ServiceLocator::disableLoadRegistry();
$classes = [];

$classesSL             = [];
$classesSLOnlyAlliases = [];
$dataSl                = new \ModulesGarden\Servers\HetznerVps\Core\SL\Data\DataSL();
foreach ($dataSl->getRegisters() as $register)
{
    $class = $dataSl->getAlias($register['class']);
    if (in_array($class, $classesSL, true) === false)
    {
        $classesSL[]             = $register['class'];
        $classesSL[]             = $class;
        $classesSLOnlyAlliases[] = $class;
    }

    if (in_array($dataSl->getRewrite($register['class']), $classesSL, true) === false)
    {
        $classesSL[] = $dataSl->getRewrite($register['class']);
    }
}
foreach ($dirs as $dir)
{
    $Directory = new RecursiveDirectoryIterator($modulePath . DS . $dir, RecursiveDirectoryIterator::KEY_AS_FILENAME | RecursiveDirectoryIterator::CURRENT_AS_FILEINFO);
    $Iterator  = new RecursiveIteratorIterator($Directory);
    $Regex     = new RegexIterator($Iterator, '/\.php$/i', RegexIterator::MATCH, RegexIterator::USE_KEY);

    foreach ($Regex as $file)
    {
        $tokens    = token_get_all(file_get_contents($file->getPathname()) . " ?>");
        $namespace = '\\';
        foreach ($tokens as $index => $token)
        {
            if (is_array($token))
            {
                if ($token[0] == \T_NAMESPACE)
                {
                    $commaIndex = $index + 1;

                    while ($tokens[$commaIndex] != ';')
                    {
                        $namespace .= trim($tokens[$commaIndex][1]);

                        $commaIndex++;
                    }
                }
                elseif ($token[0] == \T_CLASS)
                {
                    $class = str_replace([" ", "\n", "->"], "", trim($namespace, "\\") . "\\" . $tokens[$index + 2][1]);
                    if (in_array($class, $classes, true) === false && in_array($class, $classesSL, true) === false && substr($class, -1, 1) !== "\\")
                    {
                        if (strpos($class, '\null') !== false)
                        {
                            continue;
                        }
                        $classes[] = $class;
                    }
                }
            }
        }
    }
}

echo "\n\nStart build DependencyInjection: ... \n\n";
$countClass = count($classes);
$number     = 0;
$progress   = new SimpleProgress();

foreach ($classes as $className)
{
    ini_set('max_execution_time', 4000);
    $number = $number + 1;
    $progress->update($number, $countClass, $className);
    \ModulesGarden\Servers\HetznerVps\Core\DependencyInjection::loadingCache($className);
    
}
unset($progress);
echo "\n\nEnd build DependencyInjection.\n\n";

echo "\n\nStart build ServiceLocator: ... \n\n";

$countClass = count($classesSLOnlyAlliases);
$number     = 0;
$progress   = new SimpleProgress();
foreach ($classesSLOnlyAlliases as $className)
{
    ini_set('max_execution_time', 4000);

    $number = $number + 1;
    $progress->update($number, $countClass, $className);
    \ModulesGarden\Servers\HetznerVps\Core\ServiceLocator::loadingCache($className);
}
unset($progress);
echo "\n\nEnd build ServiceLocator.\n\n";
