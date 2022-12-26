<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Traits;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

trait UserDomainComponent
{
    protected $domainList = [];
    protected $domaindata = [];

    protected function buildDomainList()
    {

       if($this->getWhmcsParamByKey('producttype') == 'server' ){
        if(!$this->adminApi && method_exists($this,'loadAdminApi'))
        {
            $this->loadAdminApi();
        }
        if($this->adminApi)
        {
            if(isset($_SESSION['adminloginas'][$this->getWhmcsParamByKey('serviceid')])){
                $this->loadUserApi();
                $result = $this->userApi->domain->lists()->response;
                foreach ($result as $domain)
                {
                    $this->domainList[$domain->getName()] = $domain->getName();
                }
            }else{

                $this->domaindata = $this->adminApi->domain->listsForAdmin();
                foreach ($this->domaindata as $domain)
                {
                    $this->domainList[$this->domaindata->domain] = $this->domaindata->domain;
                }
            }
        }
    }else{
        if(!$this->userApi && method_exists($this,'loadUserApi'))
        {
            $this->loadUserApi();
        }
        if($this->userApi)
        {
            $result = $this->userApi->domain->lists()->response;

            foreach ($result as $domain)
            {
                $this->domainList[$domain->getName()] = $domain->getName();
            }
        }
    }


}

protected function getDomainList()
{

    if(!$this->domainList)
    {
        $this->buildDomainList();
    }

    return $this->domainList;
}

protected function getDomainAndSubdomainList()
{
    $domainData = $this->getDomainList();
    $subdomainData = [];

    foreach ($domainData as $domain)
    {
        $data       = [
            'domain' => $domain
        ];

        $subdomains = $this->userApi->subdomain->lists(new Models\Command\Subdomain($data));

        foreach($subdomains->subdomains as $key => $val)
        {
            if(is_numeric($key))
            {
                $subdomainData[$val->subdomain_name . '.' . $domain] = $val->subdomain_name . '.' . $domain;
            }
        }
    }

    $domainData = array_merge($domainData, $subdomainData);
    asort($domainData);
    return $domainData;
}
}
