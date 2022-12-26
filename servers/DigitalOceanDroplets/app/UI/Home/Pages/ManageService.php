<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Pages;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\PageController;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use const DS;
use function ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\sl;

/**
 * Description of ProductPage
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ManageService extends BaseContainer implements ClientArea, AdminArea {

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
