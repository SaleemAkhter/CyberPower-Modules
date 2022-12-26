<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jun 6, 2018)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Installations\Fields;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\BaseField;
use \ModulesGarden\WordpressManager\Core\Helper\BuildUrl;

/**
 * Description of ConfigNameValidator
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class CronField extends BaseField
{
    protected $id   = 'filecheckbox';
    protected $name = 'filecheckbox';
    protected $width = 3;
    protected $visible=false;
    public $autobackup_cron_min='';
    public $autobackup_cron_hour='';
    public $autobackup_cron_day='';
    public $autobackup_cron_month='';
    public $autobackup_cron_weekday='';

    public function getVisibility(){
        return $this->visible;
    }
    public function setVisibility($visibility){
         $this->visible=$visibility;
         return $this;
    }
    public function setCronData($data)
    {
        $cron=explode(",",$data);
        if(count($cron)==5){
            $this->autobackup_cron_min=$cron[0];
            $this->autobackup_cron_hour=$cron[1];
            $this->autobackup_cron_day=$cron[2];
            $this->autobackup_cron_month=$cron[3];
            $this->autobackup_cron_weekday=$cron[4];
        }
        return $this;
    }

}
