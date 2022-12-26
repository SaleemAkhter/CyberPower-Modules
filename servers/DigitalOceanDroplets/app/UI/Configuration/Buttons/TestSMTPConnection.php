<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Buttons;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons\CustomAjaxActionButton;

/**
 * Description of Reboot
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class TestSMTPConnection extends CustomAjaxActionButton implements AdminArea
{

    protected $id               = 'testSMTPConnection'; // atrybut id w tag-u
    protected $name             = 'testSMTPConnection'; // atrybut name w tagu
    protected $title            = 'testSMTPConnection';
    protected $class            = ['lu-btn lu-btn-circle lu-btn--success lu-btn-icon-only'];
    protected $icon             = "lu-zmdi lu-zmdi-refresh-alt";
    protected $customActionName = 'testSMTPConnection';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',    
    ];
    protected $requiredFields   = [
        'hostname', 'port', 'sslType', 'username', 'password'
    ];
    protected $additionalFields   = [
        'noValidateCertificate'
    ];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'makeCustomActiom(\'' . $this->customActionName . '\', ' . $this->parseCustomParams() . ', $event, \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\')';
    }

    public function returnAjaxData()
    {
        try
        {
            $config     = $this->checkAndGetFields();
            $connection = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Service\MailPiping\TestConnection($config);
            $connection->check();
            return (new HtmlDataJsonResponse())
                            ->setStatusSuccess()
                            ->setMessageAndTranslate('smtpConfigConnection');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())
                            ->setStatusError()
                            ->setMessage($ex->getMessage());
        }
    }

    private function checkAndGetFields()
    {
        $returnFields = new \stdClass();
        $formData     = $this->getFormData()['packageconfigoption'];
        foreach ($this->requiredFields as $fields)
        {
            if (empty($formData[$fields]))
            {
                throw new Exception(Lang::getInstance()->T('fillAllSmtpFields'));
            }
            $returnFields->{$fields} = $formData[$fields];
        }

        foreach ($this->additionalFields as $fields)
        {
            if (empty($formData[$fields]))
            {
                continue;
            }
            $returnFields->{$fields} = $formData[$fields];
        }

        return $returnFields;
    }

    private function getFormData()
    {
        parse_str(html_entity_decode($_POST['formFields']), $output);
        return $output;
    }

}
