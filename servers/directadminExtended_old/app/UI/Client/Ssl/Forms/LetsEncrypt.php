<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Email;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Fields\CustomSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;

class LetsEncrypt extends BaseForm implements ClientArea
{
    protected $id    = 'letsEncryptForm';
    protected $name  = 'letsEncryptForm';
    protected $title = 'letsEncryptForm';

    const LETS_ENCRYPT  = 'letsEncrypt';


    public function initContent()
    {
        $this->addDefaultActions(self::LETS_ENCRYPT);
        $this->setFormType(self::LETS_ENCRYPT)
                ->setProvider(new Providers\Ssl());

        $this->loadBasicSection()
        ->loadEntriesSection()
            ->loadDataToForm();
    }

    public function loadBasicSection()
    {
        $basicSection = (new Sections\TabSection('basicSection'))->setTitle('basicSectionTitle');
        $section = new Sections\RawSection('basicSection');

        $selectedDomain = $this->getDomainData();
        $domain        = (new Fields\Select('domains'))->notEmpty()
            ->setDefaultValue($selectedDomain)
            ->addHtmlAttribute('bi-event-change', "initReloadModal");

        $wildcard = new Fields\Switcher('wildcard');

        $email         = (new Fields\Text('email'))->notEmpty()->addValidator(new Email())->setDefaultValue($this->getWhmcsParamByKey('model')->client->email);
        $keySize   = (new Fields\Select('size'));
        $encryption = new Fields\Select('encryption');

        $section->addField($domain)
            ->addField($wildcard)
            ->addField($email)
            ->addField($keySize)
            ->addField($encryption);

        $basicSection->addSection($section);
        $this->addSection($basicSection);

        return $this;
    }

    public function loadEntriesSection()
    {
        $entriesSection = (new Sections\TabSection('entriesSection'))->setTitle('entriesSectionTitle');
        $section = new Sections\RawSection('entriesSectionRaw');

        $selectedDomain = $this->getDomainData();
        $switchersData = $this->dataProvider->loadSwitchers($selectedDomain);

        if(!empty($switchersData))
        {
            foreach($switchersData as $elem => $value)
            {
                $section->addField((new Fields\Switcher('entries_'. $value))->setRawTitle( $value));
            }
        }
        $entriesSection->addSection($section);
        $this->addSection($entriesSection);

        return $this;
    }

    protected function reloadFormStructure()
    {
        $this->loadEntriesSection();
        $this->dataProvider->reload();
        $this->loadDataToForm();
    }

    private function getDomainData()
    {
        if(empty($this->getDataFromRequest()['domains']))
        {
            return $this->getWhmcsParamByKey('domain');
        }
        else
        {
            return $this->getDataFromRequest()['domains'];
        }
    }

    private function getDataFromRequest()
    {
        return $this->getRequestValue('formData');
    }
}

