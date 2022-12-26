<?php

namespace ModulesGarden\WordpressManager\App\Http\Admin;

use ModulesGarden\WordpressManager\Core\Http\AbstractController;
use ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\App\UI\LoggerManager\Pages\LoggerPage;
use \ModulesGarden\WordpressManager\App\UI\Admin\Jobs\JobsDataTable;
use ModulesGarden\WordpressManager\App\UI\LoggerManager\Pages\OtherSettingsPage;
/**
 * Description of LoggerManager
 *
 * @author inbs
 */
class LoggerManager extends AbstractController
{

    public function index()
    {
        return Helper\view()->addElement(LoggerPage::class);
    }
    
    public function logs()
    {
        return Helper\view()->addElement(LoggerPage::class);
    }
    
    public function jobs()
    {
        return Helper\view()->addElement(JobsDataTable::class);
    }

    public function settings()
    {
        return Helper\view()->addElement(OtherSettingsPage::class);
    }
}
