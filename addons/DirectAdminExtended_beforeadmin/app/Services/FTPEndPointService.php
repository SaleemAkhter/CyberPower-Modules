<?php

namespace ModulesGarden\DirectAdminExtended\App\Services;

use ModulesGarden\DirectAdminExtended\App\Models\FTPEndPoints;

class FTPEndPointService
{
    protected $ftpEndPoint = null;

    public function __construct($id = null)
    {
        if ($id)
        {
            $this->ftpEndPoint = FTPEndPoints::factory($id);
        }
    }

    public function findAll()
    {
        return FTPEndPoints::factory()->all();
    }

    public function findById($id)
    {
        return FTPEndPoints::where('id', $id)->first();
    }

    public function findByCriteria(array $criteria)
    {
        return FTPEndPoints::factory()->where($criteria)->get();
    }

    public function create(array $data)
    {
        FTPEndPoints::create($data);
    }

    public function update(array $data)
    {
        if (!$this->ftpEndPoint)
        {
            throw new \Exception('FTPEndPoint entity has not been loaded');
        }
        $this->ftpEndPoint->fill($data)->save();
    }

    public function delete()
    {
        if (!$this->ftpEndPoint)
        {
            throw new \Exception('FTPEndPoint entity has not been loaded');
        }
        $this->ftpEndPoint->delete();
    }
}
