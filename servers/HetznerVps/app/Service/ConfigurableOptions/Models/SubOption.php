<?php

namespace ModulesGarden\Servers\HetznerVps\App\Service\ConfigurableOptions\Models;

use ModulesGarden\Servers\HetznerVps\App\Service\ConfigurableOptions\Abstracts\AbstractSerialize;

/**
 * Description of Option
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class SubOption extends AbstractSerialize
{
    /*
     * @var integer $configid
     */

    protected $configid;
    /*
     * @var string $name
     */
    protected $optionname;

    /*
     * @var string $name
     */
    protected $sortorder = 0;

    /*
     * @var string $name
     */
    protected $hidden = 0;

    /*
     * Alternative method to set required fields
     * 
     * @param string $name
     */

    public function __construct($key, $name = null)
    {
        $this->optionname = $this->prepareName($key, $name);
    }

    /*
     * Set Option Name
     * 
     * @param string $name
     * 
     * @return object $this
     */

    public function setName($name)
    {
        $this->optionname = $name;
        return $this;
    }

    /*
     * Set Option order
     * 
     * @param integer $sort
     * 
     * @return object $this
     */

    public function setSort($sort)
    {
        $this->sortorder = $sort;
        return $this;
    }

    /*
     * Set Option hidden status
     * 
     * @param integer $hidden
     * 
     * @return object $this
     */

    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /*
     * Set Config option ID
     * 
     * @param integer $configid
     * 
     * @return object $this
     */

    public function setConfigid($configid)
    {
        $this->configid = $configid;
        return $this;
    }

}
