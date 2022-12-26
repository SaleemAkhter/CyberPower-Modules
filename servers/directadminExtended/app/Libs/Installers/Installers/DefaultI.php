<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\ApplicationInstaller;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\ApplicationAdditionalFields;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Installation;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\DefaultI\ResponseBuilder;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Backup;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\DefaultI\Helper\FieldBuilder;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Plesk\Models\ApiXML;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Exceptions\ScriptNotFoundException;
use \ModulesGarden\Servers\DirectAdminExtended\App\Models\Apps;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\SortableByString;

/**
 * Description of DefaultI
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class DefaultI implements ApplicationInstaller, ApplicationAdditionalFields
{

    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\PleskApiComponent;
    private $cache = [];
    private $fieldBuilder;

    public function __construct(Interfaces\InstallerProvider $provider)
    {
        $this->loadApi($provider->getParams());
        $this->fieldBuilder = new FieldBuilder();
    }

    public function backupCreate($installationId, Models\Backup $model)
    {
        // do nothing
    }

    public function backupDelete($fileName)
    {
        // do nothing
    }

    public function backupRestore($fileName)
    {
        // do nothing
    }

    public function getBackups()
    {
        // do nothing
    }

    public function getInstallationFields($installationName)
    {
        $script            = $this->getScriptBySid($installationName);
        $data              = [
            'name'    => $script->getName(),
            'version' => $script->getVersion(),
            'vendor'  => $script->getVendor()
        ];
        $fields            = $this->fieldBuilder->getFieldsFromUrl($data);
        $basicFields       = $this->fieldBuilder->getBasicFields();
        $installationModel = ResponseBuilder::installtionFieldsModel($fields ? $fields : []);
        foreach ($basicFields as $field)
        {
            $installationModel->addField($field);
        }
        
        return $installationModel;
    }

    public function getInstallationScripts()
    {
        $result = $this->plesk->apiXML->aps->getPackagesList(new ApiXML\Aps())->get->result;
        SortableByString::sortByObject($result, 'getName');
        
        return $result;
    }

    public function getInstallations($showupdates = false)
    {
        $installations = Apps::factory()->where('domain_id', '=', $this->webspaceId)->get() ? Apps::factory()->where('domain_id', '=', $this->webspaceId)->get()->toArray() : [];
        
        return ResponseBuilder::instanceModelArrays($installations) ? ResponseBuilder::instanceModelArrays($installations) : [];
    }

    public function getScriptBySid($installationName)
    {
        $scripts = $this->getInstallationScripts();
        foreach ($scripts as $script)
        {
            if ($script->name == $installationName || $script->name == str_replace('_', ' ', $installationName))
            {
                return $script;
            }
        }

        throw new ScriptNotFoundException(self::class, sprintf('Installation script %s cannot be found', $installationName));
    }

    public function installationCreate($installationName, Models\Installation $model)
    {
        $data = array_merge($model->getFieldsParamsArray(), [
            'domain'  => $model->getDomain(),
            'name'    => str_replace('_', ' ', $installationName),
            'version' => $model->getVersion()
        ]);
        
        $this->plesk->apiXML->aps->install(new ApiXML\Aps($data));
        Apps::factory()->createApp([
            'domain_id'   => $this->webspaceId,
            'app_name'    => $data['name'],
            'app_version' => $data['version']
        ]);
    }

    public function installationDelete($installationId, Models\Installation $model)
    {
        // do nothing
    }
}
