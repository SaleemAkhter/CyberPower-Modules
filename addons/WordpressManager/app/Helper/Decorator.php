<?php


namespace ModulesGarden\WordpressManager\App\Helper;


use ModulesGarden\WordpressManager\App\Models\ModuleSettings;

class Decorator
{
    /**
     * @var ModuleSettings
     */
    private $moduleSetting;
    public function __construct()
    {
        $this->moduleSetting = new ModuleSettings();
    }

    public function getProtocols(){
        $protocols = ['1' => 'http://', '2' => 'http://www', '3' => ' https://', '4' => ' https://www'];
        if(!$this->moduleSetting->hasProtocols()){
            return $protocols;
        }
        $data = [];
        foreach ($this->moduleSetting->getProtocols() as $id){
            $data[$id] = $protocols[$id];
        }
        return  $data;
    }

}