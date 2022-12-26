<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\ApplicationInstaller;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\ApplicationAdditionalFields;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Installation;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Installatron\ResponseBuilder;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Backup;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Installatron\Helper\FieldBuilder;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\SortableByString;

/**
 * Description of Installatron
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class Installatron implements ApplicationInstaller, ApplicationAdditionalFields
{
    private $cache = [];

    /**
     *
     * @var \ModulesGarden\Servers\DirectAdminExtended\App\Interfaces\InstallerProvider
     */
    private $provider;
    private $fieldBuilder;
    
    public function __construct(Interfaces\InstallerProvider $provider)
    {
        $this->provider = $provider;
        $this->fieldBuilder = new FieldBuilder();
    }

    public function backupCreate($installationId, Backup $model)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $get = [
            'cmd' => 'backup',
            'api' => 'json',
            'id'  => $installationId,
        ];

        return $this->provider->post($get, [
                    'backupins'  => 1,
        ]);
    }

    public function backupDelete($fileName)
    {
        $get = [
            'cmd' => 'delete',
            'api' => 'json',
            'id'  => $fileName
        ];

        return $this->provider->post($get, [
                    'backupins' => 1
        ]);
    }

    public function backupRestore($fileName)
    {
        $get = [
            'cmd' => 'restore',
            'api' => 'json',
            'id'  => $fileName,
        ];

        return $this->provider->post($get, [
                    'backupins' => 1
        ]);
    }

    public function getBackups()
    {
        $response = $this->provider->get([
            'cmd' => 'backups',
            'api' => 'json'
        ]);

        return ResponseBuilder::backupsModelsArray($response['data'] ? $response['data'] : []);
    }

    public function getInstallationScripts()
    {
        if ($this->cache)
        {
            return $this->cache;
        }
        $result      = $this->provider->get([
            'cmd' => 'browser',
            'api' => 'json'
        ]);

        $response    = ResponseBuilder::installationScriptsModelsArray($result['data'] ? $result['data'] : []);

        ksort($response);
        foreach ($response as $key => $items)
        {

            SortableByString::sortByObject($items, 'getName');       
            $newItems = [];
            foreach($items as $item)
            {
                $newItems[$item->getSid()] = $item;
            }
            $response[$key] = $newItems;
        }
        $this->cache = $response;
        return $response;
    }

    public function getInstallations($showupdates = false)
    {
        $response = $this->provider->get([
            'cmd'         => 'installs',
            'showupdates' => $showupdates,
            'api'         => 'json'
        ]);
        
        return ResponseBuilder::instanceModelArrays($response['data'] ? $response['data'] : [], $this->getInstallationScripts());
    }

    public function installationCreate($installationId, Installation $model)
    {
        $get = [
            'cmd' => 'install',
            'api' => 'json',
            'id'  => $installationId,
        ];

        $modelParams = $model->getFieldsParamsArray();

        $post = [
            'url'  => $model->getProtocol().$model->getDomain().'/'.trim($model->getDirectory(). '/'),
            'application' => $model->getApplication(),
            'autoup' => (int) $modelParams['autoup'],
            'autoup_backup' => (int) $modelParams['autoup_backup'],
        ];

        if(!empty($modelParams['softdb']) && !empty($modelParams['db_user']) && !empty($modelParams['db_pass']))
        {
            $dbDetails = [
                'db_name' => $modelParams['softdb'],
                'db_user' => $modelParams['db_user'],
                'db_pass' => $modelParams['db_pass'],
                'db_prefix' => $modelParams['dbprefix'],
            ];
        } else {
            $dbDetails = [
                'db'    => 'auto'
            ];
        }

        $post =  array_merge($post, $dbDetails, $modelParams);

        return $this->provider->post($get, $post);
    }

    public function installationDelete($installationId, Installation $model)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $get = [
            'cmd' => 'uninstall',
            'api' => 'json',
            'id'  => $installationId,
        ];

        return $this->provider->post($get, [
                    'id'         => $installationId,
                    'removeins'  => $installationId
        ]);
    }

    public function getInstallationFields($installationId)
    {
        $fields = $this->fieldBuilder->getBasicFields();
        return ResponseBuilder::installtionFieldsModel($fields ? $fields : []);
    }
    
    public function getScriptBySid($sid)
    {
        $scripts = $this->getInstallationScripts();
        foreach ($scripts as $cat => $script)
        {
            if (array_key_exists($sid, $script))
            {
                return $script[$sid];
            }
        }

        return false;
    }    
}
