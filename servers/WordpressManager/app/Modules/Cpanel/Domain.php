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

namespace ModulesGarden\WordpressManager\App\Modules\Cpanel;

use \ModulesGarden\WordpressManager\App\Interfaces\DomainInterace;
/**
 * Description of Domain
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Domain implements DomainInterace
{
    /**
     *
     * @var  CPanel
     */
    private $api;
    private $dir;
    private $domain;
    private $subdomain;
    private $rootdomain;
    /**
     * @var Uapi
     */
    private $uapi;
    
    public function __construct(CPanel $api)
    {
        $this->api = $api;
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
        if($this->rootdomain && preg_match("/{$this->rootdomain}/", $this->domain )){
            $request = [
                'domain'       =>  $this->domain,
                'rootdomain' => $this->rootdomain,
                'dir'      => $this->dir,

            ];
            return $this->api->api2->SubDomain->addsubdomain($request, 'sobj');
        }
        $request = [
            'dir'       => $this->dir,
            'newdomain' => $this->domain,
            'subdomain' => $this->subdomain,
        ];
        return $this->api->api2->AddonDomain->addaddondomain($request, 'sobj');
    }

    public function exist()
    {
        //domains
        $result  = $this->uapi->exec('list_domains', "/execute/DomainInfo/");
        if($result['data']['main_domain']== $this->domain){
            return true;
        }
        //addondomains
        $result  = $this->api->api2->AddonDomain->listaddondomains([], 'array');
        foreach($result['cpanelresult']['data'] as $d){
            if($d['domain']== $this->domain){
                return true;
            }
        }
        //subdomains
        $result  = $this->api->api2->SubDomain->listsubdomains([], 'array');
        foreach($result['cpanelresult']['data'] as $d){
            if($d['domain']== $this->domain){
                return true;
            }
        }
        return false;
        
    }

    /**
     * @return Uapi
     */
    public function getUapi()
    {
        return $this->uapi;
    }

    /**
     * @param Uapi $uapi
     * @return Domain
     */
    public function setUapi($uapi)
    {
        $this->uapi = $uapi;
        return $this;
    }



    
}
