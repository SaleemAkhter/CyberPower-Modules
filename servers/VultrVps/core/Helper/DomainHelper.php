<?php

namespace ModulesGarden\Servers\VultrVps\Core\Helper;

use ModulesGarden\Servers\VultrVps\Core\ModuleConstants;

/**
 * Klasa walidująca domenę oraz rozbijająca je na poszczególne cześci
 */
class DomainHelper
{
    private $fullName;
    private $subdomain;
    private $domain;
    private $tld;
    private static $list = [];

    /**
     *
     * @param string $domain
     */
    public function __construct($domain)
    {
        $this->fullName = trim(strtolower($domain));
        $this->split();
    }

    private function loadTldList()
    {
        if (!empty(self::$list))
        {
            return;
        }

        $path = ModuleConstants::getCoreConfigDir() . DS . 'tldList' . DS . 'tld.list';
        if (!file_exists($path))
        {
            return;
        }

        $data = file($path);
        foreach ($data as $line)
        {
            /* Ignore blank lines and comments. */
            if (preg_match('#(^//)|(^\s*$)#', $line))
            {
                continue;
            }

            self::$list[] = preg_replace('/[\r\n]/', '', $line);
        }
    }

    private function split()
    {
        $this->loadTldList();
        $components = array_reverse((explode('.', $this->fullName)));

        $lastMatch = '';
        $con       = '';
        foreach ($components as $part)
        {
            $con = "{$part}.{$con}";
            $con = trim($con, '.');

            if (in_array($con, self::$list))
            {
                $lastMatch = $con;
            }
        }

        $this->tld       = $lastMatch;
        $noTld           = preg_replace('/' . preg_quote($lastMatch) . '$/', '', $this->fullName);
        $noTld           = trim($noTld, '.');
        $components      = explode('.', $noTld);
        $this->domain    = array_pop($components);
        $this->subdomain = implode('.', $components);
    }

    /**
     * Pobieranie pełnej nazwy domeny
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Pobieranie tld (bez kropki)
     * @return string
     */
    public function getTLD()
    {
        return $this->tld;
    }

    /**
     * Pobieranie tld
     * @return string
     */
    public function getTLDWithDot()
    {
        return ($this->tld != '') ? ("." . $this->tld) : "";
    }

    /**
     * Pobieranie nazwy głównej domeny (bez tld)
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Pobieranie nazwy głównej domeny z tld
     * @return string
     */
    public function getDomainWithTLD()
    {
        return $this->domain . '.' . $this->tld;
    }

    /**
     * Pobieranie subdomeny; zwraca pustry string jeżeli nie istnieje
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Sprawdzanie czy nazwa zawiera w sobie subdomenę
     * @return boolean
     */
    public function isSubdamain()
    {
        return !empty($this->subdomain);
    }

    /**
     * Sprawdzanie czy domena jest poprawna
     * @return boolean
     */
    public function isValid()
    {
        return !empty($this->domain) && !empty($this->tld);
    }
}
