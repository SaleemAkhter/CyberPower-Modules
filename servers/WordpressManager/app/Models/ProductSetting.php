<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 5, 2017)
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

namespace ModulesGarden\WordpressManager\App\Models;

use \ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\App\Models\PluginPackage;

/**
 * Description of ProductSetting
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @property int $id
 * @property int $product_id
 * @property int $enable
 * @property array $settings
 * @method static $this ofProductId($productId)
 * @method $this enable()
 */
class ProductSetting extends ExtendedEloquentModel
{
    /**
     *
     * @var int $id
     */
    protected $table = 'ProductSettings';

    /**
     *
     * @var int
     */
    protected $guarded = ['id'];

    /**
     *
     * @var array
     */
    protected $fillable   = ['product_id', 'enable', 'settings'];
    protected $softDelete = false;
    public $timestamps    = false;

    public function toArray()
    {
        $data = parent::toArray();
        return array_merge($data, $this->getSettings());
    }

    public function getSettings()
    {
        return (array) json_decode($this->settings, true);
    }

    public function setSettings(array $settings)
    {
        $this->settings = json_encode($settings, true);
        return $this;
    }

    public function isDebugMode()
    {
        return $this->getSettings()['debugMode'] == 1;
    }
    
    public function isPluginBlocked()
    {
        return $this->getSettings()['pluginBlocked'] == 1;
    }
    
    public function isPluginBlockedDelete ()
    {
        return $this->getSettings()['pluginBlockedDelete'] == 1;
    }

    public function isThemeBlockedDelete ()
    {
        return $this->getSettings()['themeBlockedDelete'] == 1;
    }

    public function isThemeBlocked()
    {
        return $this->getSettings()['themeBlocked'] == 1;
    }
    
     public function getScanInteral()
    {
        return $this->getSettings()['pluginScanInteral'];
    }
/* update */
    public function IsPageSpeedInsightsOptionEnabled()
    {
        return $this->getSettings()['pageSpeedInsightsOption'] == 1;
    }

    public function getInstallationsLimit()
    {
        return $this->getSettings()['installationsLimit'];
    }

    public function isWpUpdateNotificationMailEnabled()
    {
        return $this->getSettings()['updateWpVersionNotifications'] == 1;
    }

    public function getUpdateWpVersionNotificationInterval()
    {
        return $this->getSettings()['updateWpVersionNotificationInterval'];
    }

    public function getUpdateWpVersionNotificationTemplate()
    {
        return $this->getSettings()['updateWpVersionNotificationTemplate'];
    }

    public function getUpdateWpVersionNotificationLastRun()
    {
        return $this->getSettings()['updateWpVersionNotificationLastRun'];
    }

    public function getPluginCrobJobLastRun()
    {
        return $this->getSettings()['pluginCrobJobLastRun'];
    }

    public function getThemeCrobJobLastRun()
    {
        return $this->getSettings()['themeCrobJobLastRun'];
    }
    
    public function getTestInstallation()
    {
        return $this->getSettings()['testInstallation'];
    }
    
    public function getInstallationScripts()
    {
        return $this->getSettings()['installationScripts'];
    }
    
     public function getPluginPackages()
    {
        return (array)$this->getSettings()['pluginPackages'];
    }
    
    public function hasPluginPackages(){
        return !empty($this->getPluginPackages()) && PluginPackage::whereIn("id", $this->getPluginPackages())->where('enable',1)->count()>0;
    }
    
    public function hasInstanceImage(){
        return !empty($this->getInstanceImages()) && InstanceImage::whereIn("id", $this->getInstanceImages())->where('enable',1)->count()>0;
    }
    
    public function getInstanceImages()
    {
        return (array)$this->getSettings()['instanceImages'];
    }

    public function getCustomThemes()
    {
        return (array)$this->getSettings()['customThemes'];
    }

    public function getCustomPlugins()
    {
        return (array)$this->getSettings()['customPlugins'];
    }
    
    public function scopeEnable($query){
        return $query->where('enable', '1');
    }

    public function getAutoInstallScript()
    {
        return $this->getSettings()['autoInstallScript'];
    }

    public function getAutoInstallInstanceImage()
    {
        return $this->getSettings()['autoInstallInstanceImage'];
    }

    public function getAutoInstall()
    {
        return $this->getSettings()['autoInstall'];
    }

    public function getAutoInstallEmailTemplate()
    {
        return $this->getSettings()['autoInstallEmailTemplate'];
    }

    public function scopeOfProductId($query, $productId){
        return $query->where("product_id", $productId);
    }

    public function getAutoInstallPluginPackages()
    {
        return $this->getSettings()['autoInstallPluginPackages'];
    }


    public function getAutoInstallThemePackages()
    {
        return $this->getSettings()['autoInstallThemePackages'];
    }

    public function getAutoInstallSoftProto(){
        return $this->getSettings()['autoInstallSoftProto'];
    }

    public function getDefaultTheme(){
        return $this->getSettings()['defaultTheme'];
    }

    public function isDeleteAutoInstall(){
        return $this->getSettings()['deleteAutoInstall']=='1';
    }

}
