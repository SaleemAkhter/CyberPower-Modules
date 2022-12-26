<?php


namespace ModulesGarden\WordpressManager\App\Configuration\Addon\Update\Patch;

use Illuminate\Database\Capsule\Manager as DB;
use ModulesGarden\WordpressManager\Core\Configuration\Addon\Update\Patch\AbstractPatch;
use ModulesGarden\WordpressManager\Core\Models\Whmcs\EmailTemplate;


class DefaultPatch extends AbstractPatch
{

    public function up()
    {
        //install email templates
        $this->templates();
    }

    protected function templates()
    {
        //Service Creation Failed
        if (EmailTemplate::where('type', "product")->where('name', 'New WordPress Information')->count() == 0) {
            $entity = new EmailTemplate();
            $entity->fill(
                [
                    "type" => "product",
                    "custom" => 1,
                    "name" => "New WordPress Information",
                    "subject" => "New WordPress Information",
                    "custom" => 1,
                    "message" => "<p>Dear {\$client_name},</p>\n"
                        . "<p>Your WordPress for {\$service_product_name} has now been activated. Please keep this message for your records.</p>\n"
                        . "<p><strong>WordPress Details<br /></strong>=============================</p>"
                        . "<p>Product/Service: {\$service_product_name}</p>\n"
                        . "<p>Domain: {\$installation.domain}<br/>URL: <a href=\"{\$installation.url}\"/>{\$installation.url}</a><br/>"
                        . "Version: {\$installation.version}<br/>Directory: {\$installation.path}<br/>"
                        . "{if \$admin_username && \$admin_pass} Managament URL: <a href=\"{\$whmcs_url}/index.php?m=WordpressManager&mg-page=home&mg-action=detail&wpid={\$installation.id}\"/>{\$whmcs_url}/index.php?m=WordpressManager&mg-page=home&mg-action=detail&wpid={\$installation.id}</a><br/> "
                        . "Control Panel:  <a href=\"{\$installation.url}/{\$adminurl}\"/>{\$installation.url}/{\$adminurl}</a> <br/>"
                        . "Username: {\$admin_username}<br/>"
                        . "Password: {\$admin_pass}<br/>{/if}"
                        . "</p>"
                        . "<p>Thank you for choosing us.</p>\n"
                        . "<p>{\$signature}</p>\n"
                ]
            )->save();
        }

        if (EmailTemplate::where('type', "product")->where('name', 'WordPress Update Notification')->count() == 0) {
            $entity = new EmailTemplate();
            $entity->fill(
                [
                    "type" => "product",
                    "custom" => 1,
                    "name" => "WordPress Update Notification",
                    "subject" => "There is the new WordPress update available",
                    "custom" => 1,
                    "message" => "<p>Dear {\$client_name},</p>\n"
                        . "<p>There is the new WordPress update available for your installations.</p>\n"
                        . "<p>{\$signature}</p>\n"
                ]
            )->save();
        }
    }
}