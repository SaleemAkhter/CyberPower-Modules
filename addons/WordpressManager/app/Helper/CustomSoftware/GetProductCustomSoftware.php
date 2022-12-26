<?php

namespace ModulesGarden\WordpressManager\App\Helper\CustomSoftware;

use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use ModulesGarden\WordpressManager\App\Models\CustomTheme;
use ModulesGarden\WordpressManager\App\Models\CustomPlugin;

class GetProductCustomSoftware
{
    private $installation;

    public function __construct(int $wpid)
    {
        $this->installation = Installation::findOrFail($wpid);
    }

    public function getProductId()
    {
        return $this->installation->hosting->product->id;
    }

    public function getCustomThemesId(){
        $repository               = new ProductSettingRepository;
        $model                    = $repository->forProductId($this->getProductId());
        $customThemeIdList        = (array) $model->getSettings()['customThemes'];
        $enabledCustomThemeIdList = (new CustomTheme())->whereIn('id', $customThemeIdList)->where('enable', 1)->pluck('id')->toArray();

        return $enabledCustomThemeIdList;
    }

    public function getCustomThemeList($search = null)
    {
        $customThemeIdList = $this->getCustomThemesId();

        if($search)
        {
            $result = json_encode((new CustomTheme())->whereIn('id', $customThemeIdList)->where('name', 'LIKE', "%{$search}%")->get());
        } else
        {
            $result = json_encode((new CustomTheme())->whereIn('id', $customThemeIdList)->get());
        }

        return $result;
    }

    public function getCustomPluginsId(){
        $repository                = new ProductSettingRepository;
        $model                     = $repository->forProductId($this->getProductId());
        $customPluginIdList        = (array) $model->getSettings()['customPlugins'];
        $enabledCustomPluginIdList = (new CustomPlugin())->whereIn('id', $customPluginIdList)->where('enable', 1)->pluck('id')->toArray();

        return $enabledCustomPluginIdList;
    }

    public function getCustomPluginList($search = null)
    {
        $customPluginIdList = $this->getCustomPluginsId();

        if($search)
        {
            $result = json_encode((new CustomPlugin())->whereIn('id', $customPluginIdList)->where('name', 'LIKE', "%{$search}%")->get());
        } else
        {
            $result = json_encode((new CustomPlugin())->whereIn('id', $customPluginIdList)->get());
        }

        return $result;
    }
}
