<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Models;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Abstracts\AbstractSerialize;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Helper\TypeConstans;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;

/**
 * Description of Option
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Option extends AbstractSerialize
{
    /*
     * @var string $optionname
     */
    use Lang;

    protected $gid;
    /*
     * @var string $optionname
     */
    protected $optionname;
    /*
     * @var string $optiontype
     */
    protected $optiontype = 1;
    /*
     * @var integer $min
     */
    protected $qtyminimum = 0;
    /*
     * @var integer $max
     */
    protected $qtymaximum = 0;
    /*
     * @var integer $order
     */
    protected $order      = 0;
    /*
     * @var integer $hidden
     */
    protected $hidden     = 0;

    /*
     * @var array $subOptions
     */
    protected $subOptions = [];

    /*
     * Alternative method to set required fields
     * 
     * @param string $name, \ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Helper\TypeConstans $type
     */

    public function __construct($key, $name = null, $type = null)
    {
        $this->loadLang();
        $optionName = $lang = $this->lang->absoluteTranslate('mainContainer', 'configurableOptions', 'description', $key);
        if(stripos($optionName, '$_LANG[') !== false)
        {
            $optionName = $name;
        }

        $this->optionname = $this->prepareName($key, $optionName);
        $this->optiontype = $type;
    }

    /*
     * Add additional suboptions
     * 
     * @params \ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Models\SubOption $option
     * @return $this
     */

    public function addSubOption(SubOption $option)
    {
        $this->subOptions[] = $option;

        return $this;
    }

    /*
     * Set Option Name
     * 
     * @param string $name;
     * 
     * @return object $this
     */

    public function setName($name)
    {
        $this->optionname = $name;

        return $this;
    }

    /*
     * Set Option Name
     * 
     * @param object \ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Helper\TypeConstans
     * 
     * @return object $this
     */

    public function setType(TypeConstans $type)
    {
        $this->optiontype = $type;
        return $this;
    }

    /*
     * Set option min, require for quantity
     * 
     * @param string $min;
     * 
     * @return object $this
     */

    public function setMin($min)
    {
        $this->qtyminimum = $min;
        return $this;
    }

    /*
     * Set option max, require for quantity
     * 
     * @param string $max;
     * 
     * @return object $this
     */

    public function setMax($max)
    {
        $this->qtymaximum = $max;
        return $this;
    }

    /*
     * Set Order
     * 
     * @param integer $order;
     * 
     * @return object $this
     */

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /*
     * Set Option Name
     * 
     * @param string $name;
     * 
     * @return object $this
     */

    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /*
     * Set Group Option ID
     * 
     * @param integer $gid;
     * 
     * @return object $this
     */

    public function setGid($gid)
    {
        $this->gid = $gid;
        return $this;
    }

    public function getSubOptions()
    {
        return $this->subOptions;
    }

    public function getName()
    {
        $expoldeName = explode('|', $this->optionname);
        if (!empty($expoldeName[1]))
        {
            return $expoldeName[1];
        }
        return $this->optionname;
    }

    public function getKey()
    {
        return explode('|', $this->optionname)[0];
    }

}
