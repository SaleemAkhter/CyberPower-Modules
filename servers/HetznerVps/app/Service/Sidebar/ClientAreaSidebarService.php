<?php
/* * ********************************************************************
*  HetznerVps Product developed. (27.03.19)
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

namespace ModulesGarden\Servers\HetznerVps\App\Service\Sidebar;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\App\Models\ProductConfiguration;
use ModulesGarden\Servers\HetznerVps\App\Service\ConfigurableOptions\ConfigurableOptions;
use ModulesGarden\Servers\HetznerVps\App\UI\Configuration\Helpers\Config;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Sidebar\Sidebar;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Sidebar\SidebarItem;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Sidebar\SidebarService;
//use ModulesGarden\Servers\HetznerVps\App\Http\Client\BaseClientController;
use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class ClientAreaSidebarService
{
    use HostingService;
    use WhmcsParams;
    use ProductService;
//    use BaseClientController;

    /**
     * @var \WHMCS\View\Menu\Item
     */
    private $primarySidebar;

    /**
     * ClientAreaSidebarService constructor.
     * @param $hostingId
     */
    public function __construct($hostingId, \WHMCS\View\Menu\Item $primarySidebar)
    {
        $this->setHostingId($hostingId);
        $this->primarySidebar = $primarySidebar;
    }

    public function informationReplaceUri()
    {
        $overview = $this->primarySidebar->getChild('Service Details Overview');
        if (!is_a($overview, '\WHMCS\View\Menu\Item'))
        {
            return;
        }
        $panel = $overview->getChild('Information');
        if (!is_a($panel, '\WHMCS\View\Menu\Item'))
        {
            return;
        }
        $panel->setUri("clientarea.php?action=productdetails&id={$this->getHostingId()}");
        $panel->setAttributes([]);
        return $this;
    }

    public function changePasswordReplaceUri()
    {
        $actions = $this->primarySidebar->getChild('Service Details Overview');
        if (!is_a($actions, '\WHMCS\View\Menu\Item'))
        {
            return;
        }
        $panel = $actions->getChild('Change Password');
        if (!is_a($panel, '\WHMCS\View\Menu\Item'))
        {
            return;
        }
        $panel->setUri("clientarea.php?action=productdetails&id={$this->getHostingId()}#tabChangepw");
        $panel->setAttributes([]);
        return $this;
    }

    public function build()
    {
        /**
         * @var $sidebarService SidebarService
         */
        $sidebarService = sl("sidebar");
        $this->setProductId($this->hosting()->packageid);
        /**
         * @var $lang \ModulesGarden\Servers\HetznerVps\Core\Lang\Lang
         */
        $lang  = sl("lang");
        $order = 671;
        $whmcsParams = sl("whmcsParams")->getWhmcsParams();
        $fieldsProvider = new FieldsProvider($whmcsParams['packageid']);

        try {
            $api = new Api($whmcsParams);
            if (is_null($api->servers()->get($whmcsParams['customfields']['serverID']))) {
                unset($api);
                throw new Exception('NotFound');
            }
        } catch (Exception $e) {
            unset($api);
            return $e->getMessage();
        }

        foreach ($sidebarService->get() as $sidebar)
        {
            /**
             * @var Sidebar $sidebar
             */
            $newPanel = [
                'label' => $lang->abtr($sidebar->getTitle()),
                'order' => $order
            ];
            $order++;
            $childPanel = $this->primarySidebar->addChild($sidebar->getName(), $newPanel);
            foreach ($sidebar->getChildren() as $sidebarItem)
            {
                //acl
                if ($sidebarItem->getName() == "backup" && !$this->configuration()->isPermissionBackup() && !$this->configuration()->isPermissionBackupJob())
                {
                    continue;
                }
                else
                {
//                    if ($sidebarItem->getName() != "backup" && $this->configuration()->get("permission" . ucfirst($sidebarItem->getName())) != "on")
//                    {
//                        continue;
//                    }


//                    if (!$this->hosting || !$this->request->getSession('uid') || $this->hosting->domainstatus !== 'Active' || $this->hosting->servers->type !== 'HetznerVps')
                    if ($fieldsProvider->getField('clientArea'. ucfirst($sidebarItem->getName())) != 'on')
                    {
                        continue;
                    }
                }

                /**
                 * @var SidebarItem $sidebarItem
                 */
                $newItem = [
                    'label'   => $lang->abtr($sidebarItem->getTitle()),
                    'uri'     => str_replace('{$hostingId}', $this->getHostingId(), $sidebarItem->getHref()),
                    'order'   => $sidebarItem->getOrder(),
                    "current" => $sidebarItem->isActive()
                ];
                $childPanel->addChild($sidebarItem->getName(), $newItem);
            }
        }
    }
}