<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Numeric;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\PasswordGenerateExtended;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\TextWithButton;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\PasswordGenerate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\TextWithAttr;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
                ->setProvider(new Providers\EmailsCreate());

        $this->addSection($this->getAccountSection())
            ->addSection($this->getPasswordSection())
            ->addSection($this->getQuotaSection())
            ->addSection($this->getLimitSection())
            ->loadDataToForm();

    }

    protected function getAccountSection()
    {
        $email = (new InputGroup('emailGroup'))
            ->addInputComponent((new InputGroupElements\Text('account'))->notEmpty())
            ->addInputAddon('@', false, '@')
            ->addInputComponent(new InputGroupElements\Select('domains'))
            ->setDescription('');;

        return $email;
    }

    protected function getPasswordSection()
    {

        $passwordSection = new FormGroupSection('passwordSection');
        $password = new PasswordGenerateExtended('password');
        $password->notEmpty();
        $passwordSection->addField($password);

        return $passwordSection;
    }

    protected function getQuotaSection()
    {
        $quotaSwitcher = new ActionSwitcher('quota');
        $quotaSwitcher->addHtmlAttribute('@change', 'initReloadModal()');

        $directorySection = (new FormGroupSection('quotaSection'))
                ->addField($quotaSwitcher);

        return $directorySection;
    }
    protected function getCustomQuotaSection()
    {
        $quotaSwitcher = new TextWithAttr('customQuota');
        $quotaSwitcher->addValidator(new Numeric());

        $directorySection = (new FormGroupSection('reloadSection'))
                ->addField($quotaSwitcher);

        return $directorySection;
    }

    protected function getLimitSection()
    {
        $quotaSwitcher = new ActionSwitcher('limit');
        $quotaSwitcher->addHtmlAttribute('@change', 'initReloadModal()');

        $directorySection = (new FormGroupSection('limitSection'))
            ->addField($quotaSwitcher);

        return $directorySection;
    }

    protected function getCustomLimitSection($limit = null)
    {
        $customLimit = [
            'customLimit' => $limit
        ];

        $this->addLocalLangReplacements($customLimit);

        $section    = new FormGroupSection('customLimitSection');
        $section->addField((new Text('customLimitText'))->addValidator(new Numeric())->setDescription('customLimitReplace'));

        return $section;
    }

    protected function reloadFormStructure()
    {
        $selectedType = $this->getRequestValue('formData')['quota'];
        if ($selectedType === 'off')
        {
            $this->addSectionAfter('quotaSection', $this->getCustomQuotaSection());
        }

        $selectedLimitType = $this->getRequestValue('formData')['limit'];
        if ($selectedLimitType === 'off')
        {
            $this->addSectionAfter('limitSection', $this->getCustomLimitSection());
        }

        $this->dataProvider->reload();
        $this->loadDataToForm();
    }

    function addSectionAfter($sectionId, $newSection)
    {
        $array = $this->getSections();
        $index = array_search($sectionId, array_keys($array)) + 1;


        $size = count($array);
        if (!is_int($index) || $index < 0 || $index > $size)
        {
            return -1;
        }
        else
        {
            $temp   = array_slice($array, 0, $index);
            $temp[$newSection->getId(). $index] = $newSection;
            $this->sections = array_merge($temp, array_slice($array, $index, $size));
        }
    }

}
