<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationInstaller;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AriaField;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AppBox;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\RawSection;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class ApplicationsNew extends BaseStandaloneForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id    = 'applicationsNewPage';
    protected $name  = 'applicationsNewPage';
    protected $title = 'applicationsNewPageTab';

    public function initContent()
    {
        $section = new RawSection();
        $section->initIds('appSection');
        $fields  = $this->generateAppLists();

        foreach ($fields as $field)
        {
            $section->addField($field);
        }

        $this->addSection($section);

        $this->setSubmit(null);



    }

    private function generateAppLists()
    {
        $appHelper     = new ApplicationInstaller($this->loadRequiredParams());
        $api           = $appHelper->getInstaller();
        $data          = $api->getInstallationScripts();
        $installerName = $appHelper->getInstallerName();

        return $this->generateViewFields($data, $installerName);
    }

    private function generateViewFields(array $data, $installerName)
    {
        $fields = [];
        if (is_array(reset($data)))
        {
            foreach ($data as $cat => $list)
            {
                $each = new AriaField();
                $each->initIds(str_replace(' ', '_', $cat))
                        ->setInstaller($installerName)
                        ->setList($this->generateAppBoxes($list))
                        ->setTitle($cat);

                $fields[] = $each;
            }
        }
        else
        {
            $ariaField = new AriaField('applications');
            $ariaField->setInstaller($installerName)
                    ->setList($this->generateAppBoxes($data))
                    ->setTitle('applications');

            $fields[] = $ariaField;
        }

        return $fields;
    }

    private function generateAppBoxes($list)
    {
        $out    = [];
        $params = sl('request')->query->all();
        unset($params['mg-page']);
        foreach ($list as $each)
        {
            $image = strpos($each->getImage(), 'secure.') === false ? $each->getImage() : str_replace('secure.', '', $each->getImage());

            $appBox = new AppBox();
            $appBox->initIds(str_replace(' ', '_', $each->getName()))
                    ->setAppName($each->getName())
                    //->setAppDescription($each->getDescription())
                    ->setImage($image)
                    ->setVersion($each->getVersion())
                    ->setSid($each->getSid())
                    ->setRawUrl(BuildUrl::getUrl('applications', 'installNew', $params));

            $out[] = $appBox;
        }

        return $out;
    }
}
