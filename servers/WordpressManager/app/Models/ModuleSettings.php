<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 15, 2018)
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

use \ModulesGarden\WordpressManager\Core\Models\ModuleSettings\Model;
/**
 * Description of ModuleSettings
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 * @property string $setting
 * @property string $value
 */
class ModuleSettings extends  Model
{
    protected $primaryKey = 'setting';
    
    public function getTestInstallationId(){
        return ModuleSettings::where('setting' ,'testInstallationId')->value('value');
    }
    
    public function setTestInstallationId($id){
        $model = ModuleSettings::where('setting' ,'testInstallationId')->first();
        if(!$model instanceof  ModuleSettings){
             $model = new ModuleSettings();
        }
        $model->setting = 'testInstallationId';
        $model->value = (int) $id;
        $model->save();
        return $this;
    }

    public function getProtocols(){
        $protocols = ModuleSettings::where('setting' ,'protocols')->value('value');
        return  \json_decode($protocols, true);
    }

    public function saveProtocols(array  $protocols){
        $json = \json_encode($protocols);
        $model = ModuleSettings::where('setting' ,'protocols')->first();
        if(!$model instanceof  ModuleSettings){
            $model = new ModuleSettings();
        }
        $model->setting = 'protocols';
        $model->value = $json;
        $model->save();
        return $this;
    }

    public function hasProtocols(){
        return ModuleSettings::where('setting' ,'protocols')->count() > 0;
    }

    public function getWordpressVersion(){
        return ModuleSettings::where('setting' ,'wordpressVersion')->value('value');
    }

    public function setSettings($settings)
    {
        foreach($settings as $settingName => $value)
        {
            $model = ModuleSettings::where('setting', $settingName)->first();
            if (!$model instanceof ModuleSettings) {
                $model = new ModuleSettings();
            }
            $model->setting = $settingName;
            $model->value = $value;
            $model->save();
        }

        return $this;
    }

    public function getSettings()
    {
        return ModuleSettings::pluck('value', $this->primaryKey)->toArray();
    }
}
