<?php


namespace ModulesGarden\Servers\HetznerVps\App\Models\Whmcs;

/**
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $subject
 * @property string $message
 * @property string $attachments
 * @property string $fromname
 * @property string $fromemail
 * @property int $disabled
 * @property int $custom
 * @property string $language
 * @property string $copyto
 * @property string $blind_copy_to
 * @property int $plaintext
 * @property string $created_at
 * @property string $updated_at
 * @method static $this ofAdmin()
 * @method static $this ofGeneral()
 * @method static $this ofCustom()
 * @method static $this ofProduct()
 * @method $this ofName($name)
 */
class EmailTemplate extends \ModulesGarden\Servers\HetznerVps\Core\Models\Whmcs\EmailTemplate
{
    public function scopeOfAdmin($query)
    {
        return $query->where("type", "admin");
    }

    public function scopeOfGeneral($query)
    {
        return $query->where("type", "general");
    }

    public function scopeOfProduct($query)
    {
        return $query->where("type", "product");
    }

    public function scopeOfCustom($query)
    {
        return $query->where("custom", 1);
    }

    public function scopeOfName($query, $name)
    {
        return $query->where("name", $name);
    }

}