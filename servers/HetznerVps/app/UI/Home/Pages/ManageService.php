<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Pages;

use ModulesGarden\Servers\HetznerVps\App\Helpers\PageController;
use ModulesGarden\Servers\HetznerVps\Core\Helper\BuildUrl;
use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use const DS;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ManageService extends BaseContainer implements ClientArea, AdminArea {

    public function initContent()
    {
        $this->getPages();
    }

    public function getAssetsUrl() {
        return BuildUrl::getAssetsURL();
    }

    public function getPages() {
        $pages = new PageController($this->getWhmcsParams());
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
