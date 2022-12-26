<?php

namespace ModulesGarden\DirectAdminExtended\App\Services;

use ModulesGarden\DirectAdminExtended\App\Models\BackupPath;

class BackupPathService
{
    protected $backupPath = null;

    public function __construct($id = null)
    {
        if ($id)
        {
            $this->backupPath = BackupPath::factory($id);
        }
    }

    public function findAll()
    {
        return BackupPath::factory()->all();
    }

    public function findById($id)
    {
        return BackupPath::where('id', $id)->first();
    }

    public function findByCriteria(array $criteria)
    {
        return BackupPath::factory()->where($criteria)->get();
    }

    public function create(array $data)
    {
        BackupPath::create($data);
    }

    public function update(array $data)
    {
        if (!$this->backupPath)
        {
            throw new \Exception('FTPEndPoint entity has not been loaded');
        }
        $this->backupPath->fill($data)->save();
    }

    public function delete()
    {
        if (!$this->backupPath)
        {
            throw new \Exception('FTPEndPoint entity has not been loaded');
        }
        $this->backupPath->delete();
    }
}
