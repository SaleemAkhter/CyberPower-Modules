<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Forms\SortedFieldForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\HalfModalColumn;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\Raw12Section;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Providers\SshProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Checkbox;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\TabSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;


/**
 * @property \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Providers\SshProvider $dataProvider Description
 */
class EditSSHForm extends BaseForm implements ClientArea
{
    protected $id    = 'editSSHForm';
    protected $name  = 'editSSHForm';
    protected $title = 'editSSHForm';

    protected $keyDataSection;
    protected $keyOptionsSection;
    protected $formKeyDataSection;
    protected $formKeyOptionSection;

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new SshProvider());

        $this->loadKeyDataSection()
            ->loadKeyOptionsSection();

        $this->initFields();
            $this->loadDataToForm();
    }

    public function loadKeyDataSection()
    {

        $this->keyDataSection = (new Sections\TabSection('keyDataSection'))->setTitle('keyDataSectionTitle');
        $formSection = (new Sections\RawSection('keyDataFormSection'));

        $comment = (new Text('comment'))
            ->initIds('comment')
            ->notEmpty();

        $fingerprint = (new Text('fingerprint'))
            ->initIds('fingerprint')
            ->disableField();
        $hiddenFingerprint = (new Hidden('hiddenFingerprint'));


        $type = (new Text('type'))
            ->initIds('type')
            ->disableField();
        $hiddentype = (new Hidden('hiddentype'));

        $size = (new Text('keysize'))
            ->initIds('keysize')
            ->disableField();
        $hiddensize = (new Hidden('hiddenkeysize'));

        $formSection->addField($comment)
            ->addField($fingerprint)
            ->addField($hiddenFingerprint)
            ->addField($type)
            ->addField($hiddentype)
            ->addField($size)
            ->addField($hiddensize);

        $this->keyDataSection->addSection($formSection);
        $this->addSection($this->keyDataSection);
        return $this;
    }

    public function loadKeyOptionsSection()
    {
        $this->keyOptionsSection = (new Sections\TabSection('keyOptionsSection'))->setTitle('keyOptionsSectionTitle');
        $this->formKeyOptionSection = (new Sections\RawSection('keyOptionsFormSection'));

        $addOption = (new Select('options'))->addHtmlAttribute('bi-event-change', 'reloadVueModal')->enableMultiple();
        $this->formKeyOptionSection->addField($addOption);

        $values = stripslashes(html_entity_decode($_REQUEST['actionElementId']));
        $values = json_decode($values, true);

        $textOptions = ['command', 'environment', 'from', 'permitopen', 'tunnel'];
        $checkboxOptions = ['no-X11-forwarding', 'no-agent-forwarding', 'no-port-forwarding', 'no-pty'];

        $options = [];
        if (!$this->getRequestValue('formData')['options'])
        {

            foreach ($values['options'] as $option)
            {

                $options[] = $option['name'];
                if (in_array($option['name'], $textOptions))
                {
                    $field = (new Text($option['name']));
                }
                elseif (in_array($option['name'], $checkboxOptions))
                {
                    $field = (new Switcher($option['name']));
                }

                $field->initIds($option['name']);
                $this->formKeyOptionSection->addField($field);
            }

            $addOption->setDefaultValue($options);

        }
        $this->keyOptionsSection->addSection($this->formKeyOptionSection);
        $this->addSection($this->keyOptionsSection);
        return $this;
    }


    public function initFields()
    {
        if(!empty($this->getRequestValue('formData')['options']))
        {
            $optionsSelect = $this->formKeyOptionSection->getField('options');

            if ($optionsSelect)
            {
                $optionsSelect->setDefaultValue($this->getRequestValue('formData')['options']);
            }

            $textOptions = ['command', 'environment', 'from', 'permitopen', 'tunnel'];
            $checkboxOptions = ['no-X11-forwarding', 'no-agent-forwarding', 'no-port-forwarding', 'no-pty'];


            foreach ($this->getRequestValue('formData')['options'] as $option)
            {
                if (in_array($option, $textOptions))
                {
                    $field = (new Text($option))
                        ->initIds($option);
                }
                elseif (in_array($option, $checkboxOptions))
                {
                    $field = (new Switcher($option))
                        ->initIds($option);
                }

                $this->formKeyOptionSection->addField($field);
            }

        }

    }

    protected function reloadFormStructure()
    {
        $this->dataProvider->reload();

    }

}