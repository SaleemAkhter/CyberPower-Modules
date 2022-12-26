<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;

class SortedFieldForm extends BaseForm
{
    /**
     * @var string
     */
    protected $id    = 'sortedFieldForm';
    /**
     * @var string
     */
    protected $name  = 'sortedFieldForm';
    /**
     * @var string
     */
    protected $title = 'sortedFieldForm';

    /**
     * @var array
     */
    protected $indexContainer = [];


    /**
     * @param BaseField $field
     * @param int $index
     * @return BaseForm|void
     */
    public function addField(BaseField $field, $index = 0)
    {
        $this->addIndex($field->getId(), $index);
        return parent::addField($field);
    }

    public function addSection($section, $index = 0)
    {
        $this->addIndex($section->getId(), $index);
        return parent::addSection($section);
    }

    /**
     * @param $id
     * @param int $index
     */
    public function addIndex($id , $index = 0)
    {
        while($this->indexExists($index))
        {
            $index++;
        }

        $this->indexContainer[$index] = $id;
    }

    /**
     * @param $id
     * @return false|int|string
     */
    public function getIndexById($id)
    {
        return array_search($id, $this->indexContainer);
    }

    /**
     * @param $index
     * @return bool
     */
    public function indexExists($index)
    {
        return isset($this->indexContainer[$index]);
    }

    /**
     * @return array
     */
    public function getSortedFields()
    {
        $tmp = [];
        /**
         * add fields to array with index
         */
        foreach($this->getFields() as $field)
        {
            $tmp[$this->getIndexById($field->getId())] = $field;
        }

        /**
         * add sections to array with index
         */
        foreach($this->getSections() as $field)
        {
            $tmp[$this->getIndexById($field->getId())] = $field;
        }
        /**
         * sort by key value
         */
        ksort($tmp);

        return $tmp;
    }
}