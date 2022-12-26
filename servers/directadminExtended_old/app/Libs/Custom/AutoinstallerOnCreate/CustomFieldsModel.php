<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Custom\AutoinstallerOnCreate;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Installation;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers\InstallerEnum;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Field;

class CustomFieldsModel
{

    public function getByParams(array $params, $installerName)
    {
        $installationModel = (new Installation())->setDomain($params['domain']);
        if ($params['customfields']['Directory'])
        {
            $installationModel->setDirectory($params['customfields']['Directory']);
        }

        switch ($installerName)
        {
            case InstallerEnum::INSTALLATRON:
                return $this->getInstallatronModel($params, $installationModel);

            case InstallerEnum::SOFTACULOUS:
                return $this->getSoftaculousModel($params, $installationModel);

            case InstallerEnum::DEFAULTI:
                return $this->getDefaultModel($params, $installationModel);

            default:
                throw new \Exception('Something went wrong with selecting installer type.');
        }
    }

    private function getSoftaculousModel($params, &$installationModel)
    {
        $installationModel->addField((new Field())->setName('admin_username')->setValue($params['customfields']['Admin Username'] ? $params['customfields']['Admin Username'] : $params['username']))
                ->addField((new Field())->setName('admin_realname')->setValue($params['customfields']['Admin Username'] ? $params['customfields']['Admin Username'] : $params['username']))
                ->addField((new Field())->setName('admin_password')->setValue($params['customfields']['Admin Password'] ? $params['customfields']['Admin Password'] : $params['password']))
                ->addField((new Field())->setName('admin_email')->setValue($params['customfields']['Admin Email'] ? $params['customfields']['Admin Email'] : $params['clientsdetails']['email']))
                ->addField((new Field())->setName('admin_fname')->setValue($params['clientsdetails']['firstname']))
                ->addField((new Field())->setName('admin_lname')->setValue($params['clientsdetails']['lastname']));

        $this->addFieldIfNotEmpty($installationModel, $params['customfields']['Database Name'], 'softdb');
        $this->addFieldIfNotEmpty($installationModel, $params['customfields']['Table Prefix'], 'dbprefix');
        $this->addFieldIfNotEmpty($installationModel, $params['customfields']['Site Name'], 'site_name');
        $this->addFieldIfNotEmpty($installationModel, $params['customfields']['Site Description'], 'site_desc');

        return $installationModel;
    }

    private function getInstallatronModel($params, &$installationModel)
    {
        $installationModel->addField((new Field())->setName('login')->setValue($params['customfields']['Admin Username'] ? $params['customfields']['Admin Username'] : $params['username']))
                ->addField((new Field())->setName('passwd')->setValue($params['customfields']['Admin Username'] ? $params['customfields']['Admin Username'] : $params['password']))
                ->addField((new Field())->setName('email')->setValue($params['customfields']['Admin Email'] ? $params['customfields']['Admin Email'] : $params['clientsdetails']['email']))
                ->addField((new Field())->setName('autoup')->setValue($params['customfields']['Auto Update'] == 'yes' ? 1 : 0));

        if (!empty($params['customfields']['Database Name']) && !empty($params['customfields']['Database Password']) && !empty($params['customfields']['Database Username']))
        {
            $installationModel->addField((new Field())->setName('db-name')->setValue($params['customfields']['Database Name']))
                    ->addField((new Field())->setName('db-user')->setValue($params['customfields']['Database Username']))
                    ->addField((new Field())->setName('db-pass')->setValue($params['customfields']['Database Password']))
                    ->addField((new Field())->setName('db-prefix')->setValue($params['customfields']['Table Prefix']));
        }
        else
        {
            $installationModel->addField((new Field())->setName('db')->setValue('auto'));
        }

        $this->addFieldIfNotEmpty($installationModel, $params['customfields']['Site Name'], 'sitetitle');

        return $installationModel;
    }

    private function getDefaultModel($params, &$installationModel)
    {
        $installationModel->addField((new Field())->setName('admin_name')->setValue($params['customfields']['Admin Username'] ? $params['customfields']['Admin Username'] : $params['username']))
                ->addField((new Field())->setName('admin_password')->setValue($params['customfields']['Admin Password'] ? $params['customfields']['Admin Password'] : $params['password']))
                ->addField((new Field())->setName('admin_email')->setValue($params['customfields']['Admin Email'] ? $params['customfields']['Admin Email'] : $params['clientsdetails']['email']));

        if (!empty($params['customfields']['Database Name']) && !empty($params['customfields']['Database Password']) && !empty($params['customfields']['Database Username']))
        {
            $installationModel->addField((new Field())->setName('databaseName')->setValue($params['customfields']['Database Name']))
                    ->addField((new Field())->setName('databaseUsername')->setValue($params['customfields']['Database Username']))
                    ->addField((new Field())->setName('databasePassword')->setValue($params['customfields']['Database Password']))
                    ->addField((new Field())->setName('databasePrefix')->setValue($params['customfields']['Table Prefix']));
        }
        
        return $installationModel;
    }

    private function addFieldIfNotEmpty(&$model, $param, $fieldName)
    {
        if (!empty($param))
        {
            $model->addField((new Field())->setName($fieldName)->setValue($param));
        }
    }
}
