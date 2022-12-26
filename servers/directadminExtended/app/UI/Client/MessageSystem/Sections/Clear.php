<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Sections;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BaseSection;

/**
 * Base Form Section controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Clear extends BaseSection
{
    protected $id   = 'accountleftSection';
    protected $name = 'accountleftSection';

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);

    }
    public function initContent()
    {
        // $this->loadLang();
        // if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" )
        // {
        //     $this->loadResellerApi([],false);
        //     $messageid = $this->getRequestValue('actionElementId' ,false);
        //     $message=$this->resellerApi->messageSystem->detail($messageid);

        //     if($message){
        //         $messagedata=[];
        //         $parts=explode("&",urldecode($message));
        //         foreach ($parts as $key => $params) {
        //             $param=explode("=",$params);
        //             $messagedata[$param[0]]=$param[1];
        //         }
        //         $this->subject=$messagedata['subject'];
        //         $this->date=fromMySQLDate(date("Y-m-d H:i:s",$messagedata['time']),true);
        //         $this->from=$messagedata['name'];
        //         $this->content=$messagedata['message'];
        //     }

        // }

    }

}
