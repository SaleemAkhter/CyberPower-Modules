<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Home\Pages;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\BuildUrl;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\PageController;
/**
 * Description of ManageService
 *
 * @author Kamil
 */
class ManageService extends BaseContainer implements ClientArea{
    
    public function getAssetsUrl() {
        return BuildUrl::getAssetsURL();
    }

    public function getPages() {
        $pages = new PageController($this->whmcsParams);
        return $pages->getPages();
    }

    public function getURL($controller) {
        $params          = sl('request')->query->all();
        $params['modop'] = 'custom';
        $params['a']     = 'management';
        $params['mg-page']     = $controller;

        return 'clientarea.php?' . http_build_query($params);
    }

    public function getImageUrl($controller) {
        $file = $this->getAssetsUrl() . DS . 'img' . DS . 'servers' . DS . strtolower($controller) . '.png';
        if (file_exists($file)) {
            return $file;
        }
    }
}
