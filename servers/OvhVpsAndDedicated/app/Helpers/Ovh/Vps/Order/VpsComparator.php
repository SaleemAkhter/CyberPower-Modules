<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Order;

use ModulesGarden\OvhVpsAndDedicated\App\Models\Machine;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\ProductConfig;

/**
 * Class VpsComparator
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class VpsComparator
{

    protected $vpsesNames;

    private $vpsNameToReinstall;

    private $templateSystemId;

    /**
     * @var Client
     */
    protected $client;


    /**
     * @var ProductConfig
     */
    protected $newVpsConfig;

    public function __construct(Client $client, $vpsesNames = [])
    {
        $this->vpsesNames   = $vpsesNames;
        $this->client       = $client;
        $this->newVpsConfig = $client->getProductConfig();
    }


    public function isFitted()
    {
        $fullVpsConfig = $this->getFullConfig();

        foreach ($fullVpsConfig as $vpsConfig)
        {
            if($vpsConfig->getSubModel()->getName()  != $this->newVpsConfig->getProduct() ){
                continue;
            }
            if ($vpsConfig->sameDataCenter['name'] != $this->newVpsConfig->getLocalization())
            {
                continue;
            }
            $this->vpsNameToReinstall = $vpsConfig->getName();
            return true;
        }
        return false;
    }

    /**
     * @param $vpsName
     * @return bool
     * @deprecated
     */
    public function checkSystem($vpsName)
    {
        $templates = $this->getSystemTemplates($vpsName);

        list($system, $bits) = explode(':', $this->newVpsConfig->getSystemVersions());

        foreach ($templates as $template)
        {
            if ($template->getDistribution() != $system)
            {
                continue;
            }
            if ($template->getBitFormat() != $bits)
            {
                continue;
            }
            if (!in_array($this->newVpsConfig->getSystemLanguages(), $template->getAvailableLanguage()))
            {
                continue;
            }
            $this->templateSystemId = $template->getId();
            return true;
        }
        return false;
    }

    public function getSystemTemplates($vpsName)
    {
        $vpsRepository = new Repository($this->client->getParams());

        return $vpsRepository->get($vpsName)->templates()->allToModel();
    }

    public function getFullConfig()
    {
        $vpsRepository = new Repository($this->client->getParams());
        $config        = [];
        foreach ($this->vpsesNames as $vpsesName)
        {
            try{
                $model                 = $vpsRepository->get($vpsesName)->model();
                $model->sameDataCenter = $vpsRepository->get($vpsesName)->datacenter();
                $config[] = $model;
            }catch (\Exception $ex){
                Machine::where('name', $vpsesName )->delete();
            }
        }
        return $config;
    }

    public function getVpsNameToReinstall()
    {
        return $this->vpsNameToReinstall;
    }

    /**
     * @return array
     * @throws \Exception
     * @deprecated
     */
    public function getReinstallParams()
    {
        if(!$this->templateSystemId)
        {
            throw new \Exception('System template is not set');
        }
        return [
            'doNotSendPassword' => false,
            'templateId'        => $this->templateSystemId,
            'language'          => $this->newVpsConfig->getSystemLanguages(),
        ];
    }
}