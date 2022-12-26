<?php


namespace ModulesGarden\Servers\VultrVps\App\Helpers;


use http\Exception\InvalidArgumentException;
use ModulesGarden\Servers\VultrVps\App\Repositories\ProductSettingRepository;
use ModulesGarden\Servers\VultrVps\Core\Models\Whmcs\Hosting;
use function ModulesGarden\Servers\VultrVps\Core\Helper\sl;

class ClientAreaSidebar
{

    protected $hostingId;
    protected $primarySidebar;

    public function __construct($hostingId, \WHMCS\View\Menu\Item $primarySidebar)
    {
        if(!is_numeric($hostingId) || $hostingId <=0){
            throw new InvalidArgumentException(sprintf("Hosting ID #%s is invalid.", $hostingId));
        }
        $this->hostingId = $hostingId;
        $this->primarySidebar = $primarySidebar;
    }

    public function isHostingActiveAndValidServertType(){

        $h = "tblhosting";
        $p = "tblproducts";
        $query = Hosting::select("{$h}.id")
            ->rightJoin($p,"{$p}.id" ,"=", "{$h}.packageid")
            ->where("{$h}.id",$this->hostingId)
            ->where("{$h}.domainstatus","Active")
            ->where("{$p}.servertype","VultrVps");
        return $query->count() ==1;
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
        $panel->setUri("clientarea.php?action=productdetails&id={$this->hostingId}");
        $panel->setAttributes([]);
        return $this;
    }

    public function build()
    {
        /**
         * @var $sidebarService \ModulesGarden\Servers\VultrVps\Core\UI\Widget\Sidebar\SidebarService
         */
        $sidebarService = sl("sidebar");
        $lang  = sl("lang");
        $order = 671;
        $productConfiguraton = new ProductSettingRepository(Hosting::where('id', $this->hostingId)->value('packageid'));

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
                if(!$productConfiguraton->hasPermission($sidebarItem->getId())){
                    continue;
                }
                /**
                 * @var SidebarItem $sidebarItem
                 */
                $newItem = [
                    'label'   => $lang->abtr($sidebarItem->getTitle()),
                    'uri'     => str_replace('{$hostingId}', $this->hostingId, $sidebarItem->getHref()),
                    'order'   => $sidebarItem->getOrder(),
                    "current" => $sidebarItem->isActive()
                ];
                $childPanel->addChild($sidebarItem->getName(), $newItem);
            }
        }
    }

}