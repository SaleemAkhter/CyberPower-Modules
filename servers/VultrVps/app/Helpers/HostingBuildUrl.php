<?php


namespace ModulesGarden\Servers\VultrVps\App\Helpers;

use ModulesGarden\Servers\VultrVps\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\VultrVps\Core\Helper\isAdmin;


class HostingBuildUrl
{
    use WhmcsParams;

    public function getUrl($controller = null, $action = null, array $params = [])
    {
        $params['mg-page'] = $controller;
        if ($action)
        {
            $params['mg-action'] = $action;
        }
        if (isAdmin())
        {
            $url = 'clientsservices.php?id=' . $this->getWhmcsParamByKey('serviceid');
            return $url .'&' . http_build_query($params);
        }
        $url = 'clientarea.php?action=productdetails&id=' . $this->getWhmcsParamByKey('serviceid');
        if ($controller)
        {
            $params['modop']   = 'custom';
            $params['a']       = 'management';
        }
        return $url .'&' . http_build_query($params);
    }
}