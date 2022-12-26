<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Text extends BaseField
{
    protected $id   = 'text';
    protected $name = 'text';

    protected $prefixtext='';
    protected $suffixtext='';
    protected $prefixTranslated=false;
    protected $suffixTranslated=false;


    public function setPrefixText($prefixtext)
    {
         $this->prefixtext=$prefixtext;
         return $this;
    }
    public function getPrefixText()
    {
        return $this->prefixtext;
    }
    public function setPrefixTranslated($translated)
    {
         $this->prefixTranslated=$translated;
         return $this;
    }
    public function getPrefixTranslated()
    {
        return $this->prefixtext;
    }
    public function setSuffixText($suffixtext)
    {
         $this->suffixtext=$suffixtext;
         return $this;
    }
    public function getSuffixText()
    {
        return $this->suffixtext;
    }
    public function setSuffixTranslated($translated)
    {
         $this->suffixTranslated=$translated;
         return $this;
    }
    public function getSuffixTranslated()
    {
        return $this->suffixTranslated;
    }


}

