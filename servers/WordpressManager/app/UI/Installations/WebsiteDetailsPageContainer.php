<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use ModulesGarden\WordpressManager\App\UI\Installations\DesktopTab;
use ModulesGarden\WordpressManager\App\UI\Installations\DetailPage;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\WebsiteDetailsPageModal;
use ModulesGarden\WordpressManager\App\UI\Installations\WebsiteDetailsPage;
use ModulesGarden\WordpressManager\App\UI\Installations\WebsiteDetailsPageMobile;
use ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\TabSection;
use ModulesGarden\WordpressManager\Core\UI\Widget\TabsWidget\TabsWidget;
use function ModulesGarden\WordpressManager\Core\Helper\sl;

class WebsiteDetailsPageContainer extends BaseContainer implements ClientArea
{
    protected $id    = 'website-details-page-container';
    protected $name  = 'website-details-page-container';
    protected $title = 'website-details-page-container-title';
    protected $vueComponent = true;


    public function initContent()
    {
        $this->addElement(new WebsiteDetailsPage());
        $this->addElement(new WebsiteDetailsPageMobile());
    }

}
