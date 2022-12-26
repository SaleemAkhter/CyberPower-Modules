<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 24, 2018)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\Modules\Plesk;


use \ModulesGarden\WordpressManager\App\Interfaces\DomainInterace;
/**
 * Description of Domain
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Domain implements DomainInterace
{
    private $params;
    private $dir;
    private $domain;
    private $subdomain;
    private $rootdomain;
    
    
    public function __construct($params)
    {
        $this->params =$params;
    }

    public function setAttributes(array $attributes){
        $this->dir = $attributes['dir'];
        $this->domain = $attributes['domain'];
        $this->subdomain = $attributes['subdomain'];
        $this->rootdomain = $attributes['rootdomain'];
        return $this;
    }
    
    public function create()
    {

        $client = ApiClientFactory::fromParamsAsRoot($this->params);
        //subdomain
        if($this->rootdomain && preg_match("/{$this->rootdomain}/", $this->domain )){
            $request = [
                'parent' => $this->rootdomain,
                'name' => $this->subdomain ,
                'property' => [
                    "www_root" => $this->dir ///var/www/vhosts/{$this->params['domain']}/{$data['newDomain']}
                ]
            ];
            return $client->subdomain()->create($request);
        }
        //addondomain
        $request = [
            "name" => $this->domain,
            "webspace-name" => $this->params['domain'],
            "hosting" => [
                "www_root"  => $this->dir, // $data['newDomain']
                "php_handler_id" => 'plesk-php71-fpm'
            ]
        ];
        return  $client->site()->create($request);
    }

    public function exist()
    {
        foreach (ApiClientFactory::fromParamsAsUser($this->params)->site()->getAll() as $k => $entery)
        {
            if ($this->domain == $entery->name )
            {
                return true;
            }
        }
        return false;
        
    }

    /**
     * @param string $domain
     * @return array
     * @throws \Exception
     */
    public function findDomain($domain){
        foreach (RestFullFactory::fromParamsAsRoot($this->params)->domains()->get($domain) as $entery)
        {
            if ($domain == $entery['name'] )
            {
                return $entery;
            }
        }
        throw new \Exception(sprintf("Domain %s not found", $domain) );
    }

    
}
