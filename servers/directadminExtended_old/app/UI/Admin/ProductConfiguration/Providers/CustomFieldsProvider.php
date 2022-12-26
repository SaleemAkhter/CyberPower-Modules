<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Providers;


use ModulesGarden\Servers\DirectAdminExtended\App\Services\ConfigurableOptions\ConfigurableOptions;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\ProductConfiguration\Helper\ConfigurableOptionsBuilder;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CustomFieldsProvider extends BaseDataProvider implements AdminArea
{
    protected $customfields = [
        [
            'name'  => 'Directory',
            'title' => 'Directory',
            'type'  => 'text',
            'require'  => 'on',
        ],
        [
            'name'  => 'Database Name',
            'title' => 'Database Name',
            'type'  => 'text',
            'require'  => 'off',
        ],
        [
            'name'  => 'Database Username',
            'title' => 'Database Username',
            'type'  => 'text',
            'require'  => 'off',
        ],
        [
            'name'  => 'Database Password',
            'title' => 'Database Password',
            'type'  => 'text',
            'require'  => 'off',
        ],
        [
            'name'  => 'Table Prefix',
            'title' => 'Table Prefix',
            'type'  => 'text',
            'require'  => 'off',
        ],
        [
            'name'  => 'Site Title',
            'title' => 'Site Title',
            'type'  => 'text',
            'require'  => 'on',
        ],
        [
            'name'  => 'Site Description',
            'title' => 'Site Description',
            'type'  => 'text',
            'require'  => 'on',
        ],
        [
            'name'  => 'Language',
            'title' => 'Language',
            'type'  => 'text',
            'require'  => 'on',
        ],
    ];

    public function getCustomFields()
    {
        return $this->customfields;
    }

    public function read()
    {
    }

    public function create()
    {
        foreach ($this->customfields as $field)
        {
            if (isset($this->formData[$field['name']]) && $this->formData[$field['name']] == 'on')
            {
                \ModulesGarden\Servers\DirectAdminExtended\App\Services\CustomFields\CustomFields::create($_REQUEST['id'], $field['name'] . '|' . $field['title'], $field['type'], '', 'on', $field['require']);
            }
        }

        return (new HtmlDataJsonResponse())
            ->setStatusSuccess()
            ->setCallBackFunction('redirectToCustomFieldsTab')
            ->setMessageAndTranslate('customFieldsCreated');
    }

    public function delete()
    {
    }

    public function update()
    {
    }
}
