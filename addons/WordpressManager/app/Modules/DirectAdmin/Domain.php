<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 25, 2018)
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

namespace ModulesGarden\WordpressManager\App\Modules\DirectAdmin;

use \ModulesGarden\WordpressManager\App\Interfaces\DomainInterace;
use \ModulesGarden\WordpressManager\App\Interfaces\ChangeDomainInterface;
use \ModulesGarden\WordpressManager\App\Models\Installation;

/**
 * Description of Domain
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Domain  implements DomainInterace, ChangeDomainInterface
{
    /**
     *
     * @var  DirectAdminApi
     */
    private $api;
    private $dir;
    protected $rootdomain;
    private $domain;
    protected $subdomain;
    private $bandwidth;
    private $quota;
    private $username;

    public function __construct(DirectAdminApi $api)
    {
        $this->api = $api;
    }

    public function setAttributes(array $attributes)
    {
        $this->dir = $attributes['dir'];
        $this->domain = $attributes['domain'];
        $this->subdomain = $attributes['subdomain'];
        $this->quota = $attributes['quota'];
        $this->rootdomain = $attributes['rootdomain'];
        if(isset($attributes['username'])){
            $this->username = $attributes['username'];
        }
        
        return $this;
    }
    
    public function create()
    {
        //subdomain
        if($this->rootdomain && preg_match("/{$this->rootdomain}/", $this->domain )){
            return $this->api->addSubDomain($this->subdomain , $this->rootdomain);
        }
        //addon domain
        $request = [
            'domain'    => $this->domain,
            'bandwidth' => $this->bandwidth == null ? 'unlimited' : $this->bandwidth,
            'quota'     => $this->quota == null ? 'unlimited' : $this->quota,
            'php' => 'ON',
            
            //set 'ON' for unlimited quota
            'uquota' => $this->quota == null ? 'ON' : 'OFF',
        ];
        
        return $this->api->addAddonDomain($request);
    }

    public function exist()
    {
        $out = [];
        $user = $this->api->getUsername();
        $this->api->setUsername( $this->api->getAdminUsername());
        $mainDomains = $this->api->getAddonDomains($this->username);
        $this->api->setUsername( $user );
        foreach ($mainDomains as $domain => $v)
        {
            if($domain == $this->domain){
                return true;
            }
            foreach ($this->api->getSubDomais($domain)['list'] as $subdomain)
            {
                if(sprintf("%s.%s", $subdomain,$domain) == $this->domain){
                    return true;
                }
            }
        }
        $this->api->setUsername( $user );
        return false;
    }
    public function list()
    {
        $out = [];
        $user = $this->api->getUsername();
        $this->api->setUsername( $this->api->getAdminUsername());
        $mainDomains = $this->api->getAddonDomains($this->username);
        $this->api->setUsername( $user );
        foreach ($mainDomains as $domain => $v)
        {
            $out[]=$domain;

            foreach ($this->api->getSubDomais($domain)['list'] as $subdomain)
            {
                $out[]=$subdomain;
            }
        }
        return $out;
    }

    public function change($oldDomain, $newDomain){
        return $this->api->changeDomain($oldDomain, $newDomain);
    }
    
}
