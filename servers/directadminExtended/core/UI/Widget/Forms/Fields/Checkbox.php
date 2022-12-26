<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Checkbox extends BaseField
{
    protected $id   = 'basecheckbox';
    protected $name = 'basecheckbox';
    protected $labelposition='left';

    protected $width = 3;

    public function setLabelPosition($labelposition)
    {
        $this->labelposition = $labelposition;

        return $this;
    }
    public function getLabelPosition()
    {
       return  $this->labelposition;
    }
}
