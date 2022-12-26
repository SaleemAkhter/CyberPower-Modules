<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Buttons;

use Exception;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonAjaxCustomAction;

/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class TestSMTPConnection extends ButtonAjaxCustomAction implements AdminArea
{
    use Lang;

    protected $id               = 'testSMTPConnection'; // atrybut id w tag-u
    protected $name             = 'testSMTPConnection'; // atrybut name w tagu
    protected $title            = 'testSMTPConnection';
    protected $class            = ['lu-btn lu-btn-default lu-btn--success'];
    protected $icon             = "lu-btn__icon lu-zmdi lu-zmdi-refresh-alt";
    protected $customActionName = 'testSMTPConnection';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
    ];
    protected $requiredFields   = [
        'hostname', 'port', 'sslType', 'username', 'password'
    ];

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'makeCustomAction(\'' . $this->customActionName . '\', ' . $this->parseCustomParams() . ', $event, \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\')';
    }

    public function returnAjaxData()
    {

        try
        {
            $config     = $this->checkAndGetFields();
$connection = new \ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\MailPiping\TestConnection($config);

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
        $this->loadLang();
        $returnFields = new \stdClass();
        $formData     = $this->getFormData();

        foreach ($this->requiredFields as $fields)
        {
            $key = "packageconfigoption_". $fields;
            if (empty($formData[$key]))
            {
                throw new Exception($this->lang->translate('smtpConfigForm', 'fillAllSmtpFields'));
            }
            $returnFields->{$fields} = $formData[$key];
        }
        return $returnFields;
    }

    private function getFormData()
    {
        parse_str(html_entity_decode($_POST['formFields']), $output);

        return $output;
    }

}
