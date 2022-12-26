<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Http\Admin;

use ModulesGarden\OvhVpsAndDedicated\Core\Http\AbstractController;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;

use \ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\LoggerManager\Pages\LoggerPage;

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
}
