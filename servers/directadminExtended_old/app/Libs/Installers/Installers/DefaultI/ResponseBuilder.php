<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\DefaultI;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\InstallationScript;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Installation;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Field;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Instance;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Backup;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\Script;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\InstallerEnum;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\FieldTypeConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;

/**
 * Description of ResponseBuilder
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class ResponseBuilder
{

    /**
     * 
     * @param array $data Response from API
     * @return array
     */
    public static function installationScriptsModelsArray(array $data = [])
    {
        $array = [];
        foreach ($data as $script)
        {
            $model = new InstallationScript();
            $model->setName($script['name'])
                    ->setSoftname($script['appid'])
                    ->setDescription($script['description'])
                    ->setCategory($script['category'])
                    ->setType($script['type'])
                    ->setVersion($script['version'])
                    ->setSid($script['appid'])
                    ->setFields($script['fields'])
                    ->loadAdditionalOptions($script);

            $images = array_filter($script['images'], function($v)
            {
                return strpos($v, 'icon64_') !== false;
            });
            $model->setImage(array_pop($images));

            $scriptHelper = new Script([
                $script['category'] => [
                    $script['appid'] => $model
                ]
            ]);
            $scriptHelper->setInstallerType(InstallerEnum::INSTALLATRON)
                    ->setCategoryId($script['appid']);

            $model->setImage($scriptHelper->getImage())
                    ->setCategoryString($scriptHelper->getCategoryString());

            $array[$script['category']][$script['appid']] = $model;
        }

        return $array;
    }
    
    /**
     * Generate Installation Model 
     * 
     * @param array $data
     * @return Installation
     */
    public static function installtionFieldsModel(array $data = [])
    {
        $installation = new Installation();
        foreach ($data['obj'] as $name => $category)
        {
            if ($name === 'informations')
            {
                continue;
            }
            foreach ($category as $cat)
            {
                foreach ($cat['settings'] as $each)
                {
                    $select = false;
                    if (isset($each['choice']))
                    {
                        $select = true;
                        foreach ($each['choice'] as $key => $option)
                        {
                            unset($each['choice'][$key]);
                            $each['choice'][$option['id']] = $option['name'];
                        }
                    }

                    $field = new Field();
                    $field->setName($each['id'])
                            ->setLabel($each['id'])
                            ->setType($select ? FieldTypeConstants::SELECT : FieldTypeConstants::TEXT)
                            ->setValue($select ? $each['choice'] : $each['default_value']);

                    $installation->addField($field);
                }
            }
        }

        return $installation;
    }

    public static function instanceModelArrays(array $data = [])
    {
        foreach ($data as $each)
        {
            $instance = new Instance();
            $instance->setName($each['app_name'])
                    ->setVersion($each['app_version'])
                    ->setImage(self::getImageByAppName($each['app_name']));

            $array[] = $instance;
        }

        return $array;
    }

    private static function getImageByAppName($appName)
    {
        $iconName = preg_replace("/[^a-z0-9\.]/", "", strtolower($appName)) . '_icon.png';
        $iconSrc  = BuildUrl::getAssetsURL() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
        $iconSrc  .= 'applications' . DIRECTORY_SEPARATOR . $iconName;
        if (!file_exists($iconSrc))
        {
            $iconSrc = str_replace($iconName, 'default_icon.png', $iconSrc);
        }

        return $iconSrc;
    }
}
