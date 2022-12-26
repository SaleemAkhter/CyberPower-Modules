<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamAssasin\Pages;


use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamAssasin\Buttons\OnOff;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamAssasin\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamAssasin\Providers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;

class SpamAssasin extends BaseStandaloneForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id       = 'spamAssassin';
    protected $name     = 'spamAssassin';
    protected $title    = 'spamAssassin';
    protected $errorPage = false;

    protected $disable = false;

    public function initContent()
    {

        if ($this->isEnabledSpamAssassin() === false)
        {
            $this->disable = true;
         //   return;
        }
        if($this->errorPage === false)
        {
            $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\Spamassassin())
            ->loadSpamSection()
            ->loadDataToForm();

        }
    }

    public function loadSpamSection()
    {
        $section = new BoxSection('section');

        $section->addField(new Fields\Select('destination'))
        ->addField((new Fields\Select('score'))->setDefaultValue('5.0')->setDescription('scoreDescription')->addHtmlAttribute('onchange', "hideInputIfValue('customScore', 'custom', event)"))
        ->addField((new Fields\Text('customScore')))
        ->addField((new ActionSwitcher('noDeleteScore'))->addHtmlAttribute('onclick', "hideInputByName('deleteScore', event)"))
        ->addField((new Fields\Text('deleteScore'))->addValidator(new Validator\SpamScore())->setDescription('deleteScoreDescription'))
        ->addField((new ActionSwitcher('noSubject'))->addHtmlAttribute('onclick', "hideInputByName('subject', event)"))
        ->addField((new Fields\Text('subject')))
        ->addField((new Fields\Select('deliver'))->setDescription('deliverDescription'))
        ->addField((new Fields\Textarea('blacklist'))->setDescription('blacklistDescription'))
        ->addField((new Fields\Textarea('whitelist'))->setDescription('whitelistDescription'))->addHtmlAttribute('load', 'hideFields()');

        $this->addSection($section);

        return $this;
    }

    // ToDO:: move to helper
    public function isEnabledSpamAssassin()
    {
        try
        {
            if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
            {
                $this->loadResellerApi([],false);
                $domains=$this->resellerApi->domain->lists();
                $domainlist=$domains->getResponse();
                if(!empty($domainlist)){
                    $domain=$domainlist[0];
                    $domainname=$domain->name;
                    $data     = [
                        'domain' => $domainname
                    ];
                    $response =  $this->resellerApi->spamassassin->get(new Models\Command\Spamassassin($data))->getResponse();
                }else{
                    $result=[];
                }


            }else{
                $this->loadUserApi();
                $data   = [
                    'domain' => $this->getWhmcsParamByKey('domain')
                ];
                $response =  $this->userApi->spamassassin->get(new Models\Command\Spamassassin($data))->getResponse();
            }

            if(is_null($response) || $response[0]->getIsOn() == "no"){
                return false;
            }

            return true;
        }catch (\Exception $ex)
        {
            if(strpos($ex->getMessage(), 'not running') !== false || strpos($ex->getMessage(), 'disabled') !== false)
            {
                $this->errorPage = true;
                return false;
            }
        }

        return false;
    }

    public function getErrorPage()
    {
        return $this->errorPage;
    }

    public function getOnOffSwitcher()
    {
        $switcher = new OnOff('onOff');
        $switcher->setSwitcherDisable($this->isDisable());
        // $section->addElement($switcher);

        return $switcher->getHtml();
    }

    public function isDisable(){
     return $this->disable;
 }


}
