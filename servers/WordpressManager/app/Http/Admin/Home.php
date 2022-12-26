<?php

namespace ModulesGarden\WordpressManager\App\Http\Admin;

use ModulesGarden\WordpressManager\Core\Http\AbstractController;
use ModulesGarden\WordpressManager\Core\Helper;

/**
 * Example admin home page controler
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Home extends AbstractController
{

    /**
     * Example of redirection index page to other page from controler
     * @return type
     */
    public function Index()
    {
        return Helper\view()->addElement(\ModulesGarden\WordpressManager\App\UI\Dashboard\DashboardPage::class)
                            ->addElement(\ModulesGarden\WordpressManager\App\UI\Dashboard\InstallationPage::class);
    }
}
