<?php


use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use ModulesGarden\WordpressManager\App\Jobs\AutoInstallScriptJob;
use ModulesGarden\WordpressManager\App\Jobs\AutoInstallInstanceImageJob;
use function \ModulesGarden\WordpressManager\Core\Helper\queue;

$hookManager->register(
    function ($vars)
    {
        $params = $vars['params'];
        if(!$params['moduletype']){
            return;
        }
        elseif (!in_array($params['moduletype'], ['cpanelExtended', 'cpanel', 'directadmin', 'directadminExtended', 'plesk', 'PleskExtended'])){
            return;
        }
        //enable
        if (!ProductSetting::ofProductId($params['packageid'])->enable()->count()){
            return;
        }
        /**
         * Auto install
         * @var ProductSetting $productSetting
         */
        $productSetting = ProductSetting::ofProductId($params['packageid'])->firstOrFail();
        if(!$productSetting->getAutoInstall()  && !$productSetting->getAutoInstallScript() && !$productSetting->getAutoInstallInstanceImage()){
            return;
        }
        //auto install script
        if($productSetting->getAutoInstall() =="script" && $productSetting->getAutoInstallScript()){
            queue( AutoInstallScriptJob::class, ['hostingId' =>  $params['serviceid']]);
        }
        //auto install instance image
        if($productSetting->getAutoInstall() =="instanceIamge" &&  $productSetting->getAutoInstallInstanceImage()){
            queue( AutoInstallInstanceImageJob::class, ['hostingId' =>  $params['serviceid']]);
        }
    }, 1000
);