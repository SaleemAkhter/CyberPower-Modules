<?php

namespace ModulesGarden\WordpressManager\App\Models;

use \ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;

class UpdateMails extends ExtendedEloquentModel
{
    /**
     *
     * @var int $id
     */
    protected $table = 'UpdateMails';

    /**
     *
     * @var int
     */
    protected $guarded = ['id'];

    /**
     *
     * @var array
     */
    protected $fillable   = ['user_id', 'current_version'];
    protected $softDelete = false;
    public $timestamps    = false;

    public function toArray()
    {
        $data = parent::toArray();
        return array_merge($data, $this->getSettings());
    }

    public function getSettings()
    {
        return (array) json_decode($this->settings, true);
    }

    public function setSettings(array $settings)
    {
        $this->settings = json_encode($settings, true);
        return $this;
    }
}
