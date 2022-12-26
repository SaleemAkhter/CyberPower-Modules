<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Softaculous;

use ModulesGarden\Servers\DirectAdminExtended\Core\FileReader\Reader;
use ModulesGarden\Servers\DirectAdminExtended\Core\ModuleConstants;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\InstallationScript;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Backup;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Installation;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Field;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Instance;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\Script;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\InstallerEnum;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Softaculous\Helper\FieldBuilder;

/**
 * Description of ResponseBuilder
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class ResponseBuilder
{
    /**
     *
     * @description list of cloneable items
     * @var array
     */
    protected static $cloneableList = [];

    /**
     * Build array of script objects
     * 
     * @param array $data
     * @return array
     */
    public static function installationScriptsModelsArray(array $data = [])
    {
        $array = [];
        foreach ($data as $sid => $script)
        {
            $model = new InstallationScript();
            $model->setName($script['name'])
                    ->setSoftname($script['softname'])
                    ->setDescription($script['desc'])
                    ->setCategory($script['cat'])
                    ->setType($script['type'])
                    ->setVersion($script['ver'])
                    ->setSid($sid)
                    ->loadAdditionalOptions($script);
            $scriptHelper = new Script([
                $script['cat'] => [
                    $sid => $model
                ]
            ]);
            $scriptHelper->setInstallerType(InstallerEnum::SOFTACULOUS)
                    ->setCategoryId($sid);
            
            $model->setImage($scriptHelper->getImage())
                    ->setCategoryString($scriptHelper->getCategoryString());
            
            $array[$script['cat']][$sid] = $model;
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
        foreach ($data as $sid => $installationId)
        {
            foreach ($installationId as $instanceId => $backups)
            {
                foreach ($backups as $each)
                {
                    $backup = new Backup();
                    $backup->setId($each['name'])
                            ->setName($each['name'])
                            ->setPath($each['softpath'])
                            ->setVersion($each['ver'])
                            ->setTime($each['itime'])
                            ->setNote($each['backup_note'])
                            ->setFile($each['path'])
                            ->setFileList($each['fileindex'])
                            ->setUrl($each['softurl'])
                            ->setTitle($each['site_name'])
                            ->setEmail($each['email'])
                            ->setDbName($each['softdb'])
                            ->setDbHost($each['softdbhost'])
                            ->setDbPassword($each['softdbpass'])
                            ->setDbPrefix($each['dbprefix'])
                            ->setDbUser($each['softdbuser'])
                            ->loadAdditionalOptions($each);

                    $array[] = $backup;
                }
            }
        }

        return $array;
    }

    /**
     * Generate Installation Models
     * 
     * @param array $data
     * @return array of Installations
     */
    public static function installtionFieldsModel(array $data = [])
    {
        if(!count($data) > 0)
        {
            $data = FieldBuilder::getStandardFields();
        }
        $data = array_merge(FieldBuilder::getBasicFields(),$data);
        $installation = new Installation();
        foreach ($data as $each)
        {
            $field = new Field();
            $field->setType($each['type'])
                    ->setLabel($each['label'])
                    ->setName($each['name'])
                    ->setValue($each['value'])
                    ->setOptions($each['options']);

            if ($each['type'] == 'select')
            {
                $field->setOptions($each['options']);
            }

            $installation->addField($field);
        }

        return $installation;
    }

    /**
     * Generate Application Instances Models
     * 
     * @param array $data
     * @return array of Instances
     */
    public static function instanceModelArrays(array $data = [], array $scripts = [])
    {
        $new = [];
        foreach($scripts as $category => $installScripts)
        {
            foreach($installScripts as $each)
            {
                $new[$category][$each->getSid()] = $each;
            }
        }

        static::loadCloneableList();

        $array        = [];
        $scriptHelper = new Script($new);
        $scriptHelper->setInstallerType(InstallerEnum::SOFTACULOUS);
        foreach ($data as $type => $group)
        {
            foreach ($group as $sid => $each)
            {
                $scriptHelper->setCategoryId($type);

                $instance = new Instance();
                $instance->setId($sid)
                        ->setDomain($each['softdomain'])
                        ->setCategoryId($type)
                        ->setVersion($each['ver'])
                        ->setTime($each['itime'])
                        ->setPath($each['softpath'])
                        ->setDbName($each['softdb'])
                        ->setDbHost($each['softdbhost'])
                        ->setDbPassword($each['softdbpass'])
                        ->setDbPrefix($each['dbprefix'])
                        ->setDbUser($each['softdbuser'])
                        ->setFileList($each['fileindex'])
                        ->setTitle($each['site_name']);

                $instance->setName($scriptHelper->getName())
                        ->setImage($scriptHelper->getImage())
                        ->setCategory($scriptHelper->getCategoryString());

                static::$cloneableList[$scriptHelper->getName()] == '1' ? $instance->setIsCloneable(true) : $instance->setIsCloneable(false);
                $instance->setStaging(($each['is_staging']) ? '1' : '0');
                $instance->setCanStaging(($instance->isStaging() == '0' && $instance->isCloneable() == true) ? '1' : '0');

                $instance->loadAdditionalOptions($each);

                $array[] = $instance;

            }
        }

//            var_dump($array);
//            die();

        return $array;
    }

    public static function editInstanceModelArrays(array $data = [])
    {
        $instance = new Instance();
        $instance->setId($data['id']);
        $instance->setEditDbName($data['softdb']);
        $instance->setEditDbUser($data['softdbuser']);
        $instance->setEditDbHost($data['softdbhost']);
        $instance->setEditDbPassword($data['softdbpass']);
        $instance->setNoEmail($data['disable_notify_update']);
        $instance->setAutoUpgradePlugins(($data['auto_upgrade_plugins'] === 1) ? 'on' : 'off');
        $instance->setAutoUpgradeThemes(($data['auto_upgrade_themes'] === 1) ? 'on' : 'off');

        return $instance;
    }

    protected function loadCloneableList()
    {
        $namespace = ModuleConstants::getFullPath() .
            DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'Libs'.DIRECTORY_SEPARATOR.'Installers'.
            DIRECTORY_SEPARATOR.'Installers'.DIRECTORY_SEPARATOR.'Softaculous'.DIRECTORY_SEPARATOR.'Helper'.
            DIRECTORY_SEPARATOR.'cloneableList.yml';

        $reader = Reader::read($namespace)->get();

        static::$cloneableList = $reader;
    }
}
