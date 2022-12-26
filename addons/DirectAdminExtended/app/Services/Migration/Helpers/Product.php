<?php
/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-10-05
 * Time: 15:28
 */

namespace ModulesGarden\DirectAdminExtended\App\Services\Migration\Helpers;


use ModulesGarden\DirectAdminExtended\App\Services\Migration\Traits\OldDataBaseManger;
use ModulesGarden\DirectAdminExtended\App\Services\Migration\Traits\SwitcherFields;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;

class Product
{

        use SwitcherFields, OldDataBaseManger;

        protected $oldTable = 'directadmin_extended_product';

        public function run(){
            if($this->checkOldConfiguration()){
                $this->migrate();
            }
        }

        private function migrate(){
            $oldSettings = $this->getOldSettings();
            foreach($oldSettings as $setting){
                if($this->checkExistsSetting($setting->product_id, $setting->setting)){
                    continue;
                }

                $repo = new Repository();
                $repo->updateProductSetting($setting->product_id, $setting->setting, $this->replaceValue($setting->setting, $setting->value));
            }
            $this->removeTableAfterMigrate();
        }

        private function checkExistsSetting($productID, $settingName)
        {
            $repo = new Repository();
            $setting = $repo->getProductSettings($productID)[$settingName];

            if(is_null($setting)){
                return false;
            }
            return true;
        }
        private function replaceValue($setting, $value)
        {
            if($this->isSwitcher($setting)){
                return $this->getSwitcherValue($value);
            }

            return $value;
        }



}