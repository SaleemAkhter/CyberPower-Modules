<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\HalfModalColumn;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\NoBoxSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\Raw12Section;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MailingLists\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;

class Settings extends BaseForm implements ClientArea
{
    protected $id    = 'settingsForm';
    protected $name  = 'settingsForm';
    protected $title = 'settingsForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\MailingListsSettings());

        $this->loadBasicSection()
            ->loadAdvancedSection()
            ->loadOtherSection()
            ->loadDataToForm();
    }

    protected function loadBasicSection()
    {
        $basicSection = (new Sections\TabSection('basicSection'))->setTitle('basicSectionTitle');
        $firstColumn  = new HalfModalColumn('firstColumn');
        $secondColumn = new HalfModalColumn('secondColumn');
        $firstColumn->addField(new Fields\Text('options[adminPasswd]'))
            ->addField(new Fields\Text('options[replyTo]'))
            ->addField(new Fields\Text('options[digestIssue]'))
            ->addField(new Fields\Text('options[digestMaxdays]'))
            ->addField(new Fields\Text('options[precedence]'))
            ->addField(new Fields\Text('options[subjectPrefix]'));
        $secondColumn->addField((new Fields\Text('options[approvePasswd]'))->assureRepeatedPassword())
            ->addField(new Fields\Text('options[restrictPost]'))
            ->addField(new Fields\Text('options[digestVolume]'))
            ->addField(new Fields\Text('options[digestMaxlines]'))
            ->addField(new Fields\Text('options[moderator]'))
            ->addField(new Fields\Text('options[maxlength]'));

        $basicSection->addSection($firstColumn)
            ->addSection($secondColumn)
            ->addSection((new Raw12Section('desc'))->addField(new Fields\Text('options[description]')));

        $this->addSection($basicSection);

        return $this;
    }

    protected function loadOtherSection()
    {
        $otherSection = (new Sections\TabSection('otherSection'))->setTitle('otherSectionTitle');
        $formSection  = new Sections\RawSection('formSection');
        $formSection->addField(new Fields\Textarea('options[tabooBody]'))
            ->addField(new Fields\Textarea('options[tabooHeaders]'))
            ->addField(new Fields\Textarea('options[messageFooter]'))
            ->addField(new Fields\Textarea('options[messageFronter]'))
            ->addField(new Fields\Textarea('options[messageHeaders]'))
            ->addField(new Fields\Hidden('name'));

        $otherSection->addSection($formSection);

        $this->addSection($otherSection);

        return $this;
    }

    protected function loadAdvancedSection()
    {
        $advancedSection = (new Sections\TabSection('advanced'))->setTitle('advancedSectionTitle');

        $firstColumn  = new HalfModalColumn('firstColumn');
        $secondColumn = new HalfModalColumn('secondColumn');
        $firstColumn
            ->addField(new Fields\Select('options[getAccess]'))
            ->addField(new Fields\Select('options[infoAccess]'))
            ->addField(new Fields\Select('options[whichAccess]'))
            ->addField(new Fields\Select('options[welcome]'))
            ->addField(new Fields\Select('options[moderate]'))
            ->addField(new Fields\Select('options[strip]'))
            ->addField(new Fields\Select('options[subscribePolicy]'));

        $secondColumn
            ->addField(new Fields\Select('options[administrivia]'))
            ->addField(new Fields\Select('options[indexAccess]'))
            ->addField(new Fields\Select('options[introAccess]'))
            ->addField(new Fields\Select('options[whoAccess]'))
            ->addField(new Fields\Select('options[mungedomain]'))
            ->addField(new Fields\Select('options[purgeReceived]'))
            ->addField(new Fields\Select('options[unsubscribePolicy]'));


        $advancedSection->addSection($firstColumn)
            ->addSection($secondColumn)
            ->addSection((new Raw12Section('info'))->addField(new Fields\Textarea('options[info]')));

        $this->addSection($advancedSection);

        return $this;
    }
}
