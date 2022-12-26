<?php


namespace ModulesGarden\Servers\VultrVps\App\Helpers;


use ModulesGarden\Servers\VultrVps\Core\Models\Whmcs\CustomField;
use ModulesGarden\Servers\VultrVps\Core\Models\Whmcs\Hosting;

class HostingCustomField
{
    const FIELD_TYPE = 'product';
    protected $hosting;

    /**
     * HostingCustomField constructor.
     * @param $hosting
     */
    public function __construct(Hosting $hosting)
    {
        $this->hosting = $hosting;
    }

    public function get($name)
    {
         $customField = CustomField::where('type', self::FIELD_TYPE)
            ->where('relid', $this->hosting->packageid)
            ->where("fieldname","like",$name."|%" )
            ->firstOrFail();
        return $customField->getValueByRelid($this->hosting->id);
    }
}