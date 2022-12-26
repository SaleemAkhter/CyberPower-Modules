<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Helpers;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Textarea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Password;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Field;

/**
 * Description of FieldInstallerBuilder
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class FieldInstallerBuilder
{

    /**
     * Execute method by type(text/textarea/select/checkbox)
     * 
     * @param Field $field
     * @throws \Exception if method(type) does not exist
     */
    protected $adminEmail;

    protected $customFillFields = [
        'admin_email'   => 'getAdminEmail',
    ];

    /**
     * @param $adminEmail
     * @return $this
     */
    public function setAdminEmail($adminEmail = "")
    {
        $this->adminEmail = $adminEmail;

        return $this;
    }

    public function buildField(Field $field)
    {
        $method = $field->getType();
        if (!method_exists($this, $method))
        {
            throw new \Exception('Field Installer Builder: ' . $method . ' type does not exist');
        }
        return $this->{$method}($field);
    }

    /**
     * 
     * @param Field $field
     * @return Text
     */
    public function text(Field $field)
    {
        $text = new Text();
        $text->initIds($field->getName())
                ->setDefaultValue($this->getValue($field));

        return $text;
    }

    /**
     * 
     * @param Field $field
     * @return Textarea
     */
    public function textarea(Field $field)
    {
        $textarea = new Textarea();
        $textarea->initIds($field->getName())
                ->setDefaultValue($field->getValue());

        return $textarea;
    }

    /**
     * 
     * @param Field $field
     * @return Password
     */
    public function password(Field $field)
    {
        $password = new Password();
        $password->initIds($field->getName())
                ->setDefaultValue($field->getValue());

        return $password;
    }

    /**
     * 
     * @param Field $field
     * @return Switcher
     */
    public function checkbox(Field $field)
    {
        $switcher = new Switcher();
        $switcher->initIds($field->getName())
                ->setDefaultValue($field->getValue());

        return $switcher;
    }

    /**
     * 
     * @param Field $field
     * @return Select
     */
    public function select(Field $field)
    {
        $select = new Select();
        $select->initIds($field->getName())
                ->setAvailableValues($field->getOptions() ? $field->getOptions() : $field->getValue());

        return $select;
    }
    private function getValue(Field $field){
        if(array_key_exists($field->getName(), $this->customFillFields)){

            return $this->{$this->customFillFields[$field->getName()]}();
        }

        return $field->getValue();
    }

    private function getAdminEmail()
    {
        return $this->adminEmail;
    }

}
