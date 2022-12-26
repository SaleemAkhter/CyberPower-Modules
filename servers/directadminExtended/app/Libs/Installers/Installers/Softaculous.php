<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\ApplicationInstaller;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\ApplicationAdditionalFields;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\CanClone;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\CanEdit;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\CanPushStaging;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Softaculous\Helper\FieldBuilder;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Softaculous\ResponseBuilder;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Installation;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Backup;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\SortableByString;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Softaculous\Helper\AutoId;

/**
 * Description of Softaculous
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class Softaculous implements ApplicationInstaller, ApplicationAdditionalFields, CanClone, CanPushStaging, CanEdit
{
    private $cache = [];

    /**
     *
     * @var \ModulesGarden\Servers\DirectAdminExtended\App\Interfaces\InstallerProvider
     */
    private $provider;
    private $fieldBuilder;
    // InstallerProvider
    function __construct(Interfaces\InstallerProvider $provider)
    {
        $this->fieldBuilder = new FieldBuilder();
        $this->provider     = $provider;
    }

    function setProvider($provider)
    {
        $this->provider = $provider;
    }

    function getProvider()
    {
        return $this->provider;
    }

    public function getInstalation($id)
    {
        $response = $this->provider->get([
            'act'          => 'editdetail',
            'api'          => 'json',
            'insid'        => $id
        ]);

        $response['userins']['id'] = $id;

        return ResponseBuilder::editInstanceModelArrays($response['userins']);
    }

    public function getInstallations($showupdates = false)
    {
        $response = $this->provider->get([
                    'act'         => 'installations',
                    'api'         => 'json'
                ]);

        return ResponseBuilder::instanceModelArrays($response['installations'] ? $response['installations'] : [],$this->getInstallationScripts());
    }

    public function getInstallationScripts()
    {
        if ($this->cache)
        {
            return $this->cache;
        }
        $result    = $this->provider->get([
            'act' => 'home',
            'api' => 'json'
        ]);
        $response    = ResponseBuilder::installationScriptsModelsArray($result['iscripts'] ? $result['iscripts'] : []);
        ksort($response);
        foreach ($response as $key => $items)
        {
            SortableByString::sortByObject($items, 'getName');
            $response[$key] = $items;
        }

        $this->cache = $response;
        return $response;
    }

    public function installationCreate($scriptId, Installation $model)
    {
        if (!$this->cache)
        {
            $this->getInstallationScripts();
        }
        if (!$this->getScriptBySid($scriptId))
        {
            throw new \Exception(sprintf('Script \'%s\' Not Found', $scriptId));
        }

        $post = array_merge($model->getFieldsParamsArray(), [
            'softdomain'    => $model->getDomain(),
            'softdirectory' => $model->getDirectory(),
            'softsubmit'    => 1
        ]);

        if($model->getOverwriteExisting() == 'on')
        {
            $post['overwrite_existing'] = true;
        }
        // Is JS / PERL or PHP
        if ($this->getScriptBySid($scriptId)->getType() == 'js')
        {
            return $this->provider->post([
                        'act'  => 'js',
                        'api'  => 'json',
                        'soft' => $scriptId,
                            ], $post);
        }
        elseif ($this->getScriptBySid($scriptId)->getType() == 'perl')
        {
            return $this->provider->post([
                        'act'  => 'perl',
                        'api'  => 'json',
                        'soft' => $scriptId,
                            ], $post);
        }
        else
        {
            return $this->provider->post([
                        'act'  => 'software',
                        'api'  => 'json',
                        'soft' => $scriptId,
                            ], $post);
        }
    }

    public function installationEdit($id, $data)
    {
        if (!$id)
        {
            throw new \Exception("Installation ID cannot be empty");
        }

        $get = [
            'act'  => 'editdetail',
            'api'  => 'json',
            'insid' => $id
        ];

        $post = [
            'editins'     => '1',
            'edit_dbname' => $data['editDbName'],
            'edit_dbuser' => $data['editDbUser'],
            'edit_dbpass' => $data['editDbPassword'],
            'edit_dbhost' => $data['editDbHost']
        ];

        if(strpos($id, '26_') !== false) {
            $post['auto_upgrade_plugins'] = ($data['autoUpgradePlugins'] === 'on') ? '1' : '0';
            $post['auto_upgrade_themes'] = ($data['autoUpgradeThemes'] === 'on') ? '1' : '0';
        }

        return $this->provider->post($get, $post, false);
    }

    public function installationDelete($installationId, Installation $model)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $get = [
            'act'   => 'remove',
            'api'   => 'json',
            'insid' => $installationId
        ];

        return $this->provider->post($get, [
                    'remove_dir'     => $model->getRemoveDir(),
                    'remove_db'      => $model->getRemoveDb(),
                    'remove_datadir' => $model->getRemoveDatadir(),
                    'remove_wwwdir'  => $model->getRemoveWwwdir(),
                    'removeins'      => 1
        ]);
    }

    public function installationClone($installationId, $data)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }

        $post = [
            'softsubmit'    => '1',
            'softdomain'    => $data['domain'],
            'softdirectory' => $data['directory'],
            'softdb'        => $data['softdb'],
        ];

        return $this->provider->post([
                    'act'   => 'sclone',
                    'api'   => 'json',
                    'insid' => $installationId,
                        ], $post);
    }

    public function installationUpgrade($installationId, $post)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $post = ['softsubmit' => 1];

        return $this->provider->post([
                    'act'   => 'upgrade',
                    'api'   => $this->api,
                    'insid' => $installationId,
                        ], $post);
    }

    public function installationDetail($installationId)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }

        return $this->provider->get([
                    'act'   => 'editdetail',
                    'api'   => 'json',
                    'insid' => $installationId,
        ]);
    }

    public function getBackups()
    {
//        if(!$installationId){
//            throw new \Exception("Installation ID cannot be empty");
//        }
        $response = $this->provider->get([
            'act' => 'backups',
            'api' => 'json',
                //                'insid' =>$installationId,
        ]);

        return ResponseBuilder::backupsModelsArray($response['backups'] ? $response['backups'] : []);
    }

    public function backupCreate($installationId, Backup $model)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $get = [
            'act'   => 'backup',
            'api'   => 'json',
            'insid' => $installationId,
        ];

        return $this->provider->post($get, [
                    'backupins' => 1
        ]);
    }

    public function signOn($installationId)
    {
        return $this->provider->get([
                    'act'   => 'sign_on',
                    'api'   => 'json',
                    'insid' => $installationId,
        ]);
    }

    public function backupDelete($fileName)
    {
        $get = [
            'act'    => 'backups',
            'api'    => 'json',
            'remove' => $fileName,
        ];

        return $this->provider->post($get, [
                    'in_sid' => $fileName
        ]);
    }

    public function backupRestore($fileName)
    {
        $get = [
            'act'     => 'restore',
            'api'     => 'json',
            'restore' => $fileName,
        ];

        return $this->provider->post($get, [
                    'restore_dir'     => 1,
                    'restore_db'      => 1,
                    'restore_datadir' => 1,
                    'restore_wwwdir'  => 1,
                    'restore_ins'     => 1
        ]);
    }

    public function backupDownload($fileName)
    {
        return $this->provider->get([
                    'act'      => 'backups',
                    'api'      => 'json',
                    'download' => $fileName,
        ]);
    }

    public function getInstallationFields($installationId)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $softname   = $this->getScriptBySid($installationId)->getSoftname();
        $fields     = array_merge([$this->getSchemaField()], $this->fieldBuilder->buildFields($softname));

        return ResponseBuilder::installtionFieldsModel($fields ? $fields : []);
    }

    public function getScriptBySid($sid)
    {
        $scripts = $this->getInstallationScripts();
        foreach ($scripts as $cat => $script)
        {
            foreach ($script as $key => $item)
            {
                if ($item->getSid() == $sid)
                {
                    return $item;
                }
            }
        }

        return false;
    }

    public function loginIntoWordpress($installationId)
    {
        $autoId = (new AutoId())->generateAutoId();

        $get = [
            'act'   => 'sign_on',
            'api'   => 'json',
            'insid' => $installationId,
            'autoid' => $autoId
        ];

        return $this->provider->get($get);
    }

    public function installationStaging($installationId, $data = [])
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }

        $post = [
            'softsubmit'    => '1',
            'softdomain'    => $data['domain'],
            'softdirectory' => $data['directory'],
            'softdb'        => $data['softdb'],
        ];

        $result =  $this->provider->post([
            'act'   => 'staging',
            'insid' => $installationId,
        ], $post);

        return $result;
    }

    public function installationPushToLive($installationId)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }

        $post = [
            'softsubmit'    => '1',
        ];

        $result =  $this->provider->post([
            'act'   => 'pushtolive',
            'insid' => $installationId,
        ], $post);

        return $result;
    }

    protected function getSchemaField()
    {
        return [
            'type'      => 'select',
            'label'     => 'schema',
            'name'      => 'softproto',
            'options'   => [
                1       => 'http://' ,
                2       => 'http://www.',
                3       => 'https://',
                4       => 'https://www.'
            ]
        ];
    }
}
