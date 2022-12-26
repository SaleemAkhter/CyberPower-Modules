<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Account;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Domain;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Others\Label;

class Show extends BaseContainer implements ClientArea
{
    use DirectAdminAPIComponent, Lang;

    protected $id    = 'show';
    protected $name  = 'show';
    protected $title = null;

    protected $info = false;
    protected $content='';
    protected $subject='';
    protected $date='';
    protected $from='';

    public $messageinfo;


    public function initContent()
    {
        $this->loadLang();
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        {
            $this->loadResellerApi([],false);
            $messageid = $this->getRequestValue('actionElementId' ,false);
            $message=$this->resellerApi->messageSystem->detail($messageid);

            if($message){
                $messagedata=[];
                $parts=explode("&",urldecode($message));
                foreach ($parts as $key => $params) {
                    $param=explode("=",$params);
                    $messagedata[$param[0]]=$param[1];
                }
                $this->subject=$messagedata['subject'];
                $this->date=fromMySQLDate(date("Y-m-d H:i:s",$messagedata['time']),true);
                $this->from=$messagedata['name'];
                $this->content=$messagedata['message'];
            }

        }
        // $this->loadUserApi();

        $this->messageinfo = ['some'=>'info'];
    }

    public function __call($name, $arguments = [])
    {
        if(method_exists($this->domainObject, $name)) {
            switch ($arguments[0]) {
                case 'label':
                    return $this->getLabel($this->domainObject->{$name}());
                case 'translate':
                    return $this->lang->absoluteTranslate('addonCA', 'addonDomains', 'infoModal', 'domainStatus', $this->domainObject->{$name}());
                default:
                    return $this->domainObject->{$name}();
            }
        }

        return "";
    }
    public function getSubject()
    {
        return $this->subject;
    }
    public function getMessageContent()
    {
        return $this->content;
    }
    public function getMessageDate()
    {
        return $this->date;
    }
    public function getMessageType()
    {
        return $this->from;
    }


}
