<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Sections;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\InputGroupElements;

/**
 * InputGroup section controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class InputGroup extends BaseSection
{
    protected $id   = 'inputGroup';
    protected $name = 'inputGroup';
    
    public function addInputComponent($component)
    {
        $this->addField($component);
        
        return $this;
    }
    
    public function addTextField($initName, $placecholder = false, $notEmpty = true)
    {
        $newTextField = new InputGroupElements\Text($initName);
        if ($notEmpty)
        {
            $newTextField->notEmpty();
        }
        
        if ($placecholder)
        {
            $newTextField->setPlaceholder($placecholder);
        }
        
        $this->addInputComponent($newTextField);
        
        return $this;
    }
    
    public function addInputAddon($initName, $title = false, $rawTitle = false)
    {
        $newAddonField = new InputGroupElements\InputAddon();
        $newAddonField ->setName($initName);
        
        if ($title)
        {
            $newAddonField->setTitle($title);
        }
        
        if ($rawTitle)
        {
            $newAddonField->setRawTitle($rawTitle);
        }
        
        $this->addInputComponent($newAddonField);        
        
        return $this;
    }
}
