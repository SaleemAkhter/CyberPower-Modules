<?php


namespace ModulesGarden\WordpressManager\App\Helper;


use ModulesGarden\WordpressManager\Core\FileReader\Reader\Json;
use ModulesGarden\WordpressManager\Core\Lang\Lang;
use ModulesGarden\WordpressManager\Core\ModuleConstants;

class LangConveter extends  Lang
{
    protected  $lang;

    /**
     * LangConveter constructor.
     * @param $lang
     */
    public function __construct($lang=null, $userId=null)
    {
        $this->lang = $lang;
        if(is_null($lang)){
            $this->lang = $this->getLang();
        }
        if(is_numeric($userId)){
            $this->lang = $this->getByUserIdOrDefaultConfiguration($userId);
        }
    }

    private function getLangMap(){

        $json  = new Json('languageMap.json', ModuleConstants::getDevConfigDir());
        return (array)$json->get();
    }

    public function getLang(){
        if (isset($_SESSION['Language']))
        { // GET LANG FROM SESSION
            return strtolower($_SESSION['Language']);
        }
        if (isset($_SESSION['uid']) && $this->getLangByUserId($_SESSION['uid']))
        {
            return $this->getLangByUserId($_SESSION['uid']);
        }
        if ($this->getDefaultConfigLang())
        {
            return $this->getDefaultConfigLang();
        }
        return 'english';
    }

    public function getByUserIdOrDefaultConfiguration($userId){
        $lang = $this->getLangByUserId($userId);
        if($lang ){
            return $lang;
        }
        if ($this->getDefaultConfigLang())
        {
            return $this->getDefaultConfigLang();
        }
        return 'english';
    }

    public function convert(){
        $langMap = $this->getLangMap();
        return $langMap[$this->lang];
    }


}