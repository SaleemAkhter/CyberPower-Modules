<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Installatron;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\InstallationScript;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Installation;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Field;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Instance;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Backup;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\Script;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\InstallerEnum;

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
            $model  = new InstallationScript();
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
     * Build array of backup objects
     * 
     * @param array $data
     * @return Backup
     */
    public static function backupsModelsArray(array $data = [])
    {
        $array = [];
        foreach ($data as $each)
        {
            $backup = new Backup();
            $backup->setId($each['id'])
                    ->setName($each['backup-filename'])
                    ->setPath($each['path'])
                    ->setVersion($each['version'])
                    ->setTime($each['time'])
                    ->setNote($each['backup-note'])
                    ->setFile($each['file'])
                    ->setFileList($each['list-files'])
                    ->setUrl($each['url'])
                    ->setTitle($each['title'])
                    ->setEmail($each['cf-email'])
                    ->setDbHost($each['db-host'])
                    ->setDbPassword($each['db-pass'])
                    ->setDbName($each['db-name'])
                    ->setDbUser($each['db-user'])
                    ->setDbPrefix($each['db-prefix'])
                    ->loadAdditionalOptions($each);

            $array[] = $backup;
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
        foreach ($data as $name => $each)
        {
            $field = new Field();
            $field->setName($each->getName())
                    ->setLabel($each->getLabel())
                    ->setType($each->getType())
                    ->setValue($each->getValue());
            
            $installation->addField($field);
        }

        return $installation;
    }

    public static function instanceModelArrays(array $data = [], array $scripts = [])
    {
        $array        = [];
        $scriptHelper = new Script($scripts);
        $scriptHelper->setInstallerType(InstallerEnum::INSTALLATRON);
        foreach ($data as $each)
        {
            $scriptHelper->setCategoryId($each['installer']);

            $instance = new Instance();
            $instance->setId($each['id'])
                    ->setVersion($each['version'])
                    ->setTime($each['time'])
                    ->setFileList($each['list-files'])
                    ->setPath($each['path'])
                    ->setUrl($each['url'])
                    ->setTitle($each['title'])
                    ->setDbHost($each['db-host'])
                    ->setDbPassword($each['db-pass'])
                    ->setDbName($each['db-name'])
                    ->setDbUser($each['db-user'])
                    ->setDbPrefix($each['db-prefix'])
                    ->setName($scriptHelper->getName())
                    ->setCategory($scriptHelper->getCategoryString())
                    ->setImage($scriptHelper->getImage())
                    ->loadAdditionalOptions($each);

            $array[] = $instance;
        }
        
        return $array;
    }
}
