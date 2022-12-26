<?php

namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => '2.1.0',
    'version' => '2.1.0.0',
    'aliases' => 
    array (
    ),
    'reference' => NULL,
    'name' => 'modulesgarden/cloudbilling',
  ),
  'versions' => 
  array (
    'adbario/php-dot-notation' => 
    array (
      'pretty_version' => '2.1.0',
      'version' => '2.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '895fe4bb153ac875c61a6fba658ded45405e73a4',
    ),
    'brick/math' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'google/auth' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'google/cloud-bigquery' => 
    array (
      'pretty_version' => 'v1.20.0',
      'version' => '1.20.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '07ef50177ef519849078e2ab46cc462ff218105a',
    ),
    'google/cloud-core' => 
    array (
      'pretty_version' => 'v1.41.0',
      'version' => '1.41.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '2e58627e1c4f1417631ba4b0a1098b66ac98665c',
    ),
    'guzzlehttp/guzzle' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'guzzlehttp/promises' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'guzzlehttp/psr7' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'modulesgarden/cloudbilling' => 
    array (
      'pretty_version' => '2.1.0',
      'version' => '2.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'monolog/monolog' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'mso/idna-convert' => 
    array (
      'pretty_version' => 'v1.1.0',
      'version' => '1.1.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'a6dfb6f87611e3a89d2eec4924a0f51db755c573',
    ),
    'piwik/ini' => 
    array (
      'pretty_version' => '1.0.6',
      'version' => '1.0.6.0',
      'aliases' => 
      array (
      ),
      'reference' => 'bd2711ba4d5e20e4ca09b6829dc2831576b59dc3',
    ),
    'psr/cache' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'psr/cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/container' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'psr/http-message' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'psr/log' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'psr/simple-cache' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'psr/simple-cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'ralouphie/getallheaders' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'ramsey/collection' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'ramsey/uuid' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'rappasoft/laravel-helpers' => 
    array (
      'pretty_version' => '1.0.2',
      'version' => '1.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'c8dfa1e979437528262725ebe99c2e6383b24c16',
    ),
    'rize/uri-template' => 
    array (
      'pretty_version' => '0.3.2',
      'version' => '0.3.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '9e5fdd5c47147aa5adf7f760002ee591ed37b9ca',
    ),
    'symfony/cache' => 
    array (
      'pretty_version' => 'v3.4.20',
      'version' => '3.4.20.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd31c2a1b80029d885307db47405daeffafcda759',
    ),
    'symfony/dependency-injection' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/http-foundation' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-apcu' => 
    array (
      'pretty_version' => 'v1.10.0',
      'version' => '1.10.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '19e1b73bf255265ad0b568f81766ae2a3266d8d2',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/polyfill-php70' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'symfony/yaml' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
