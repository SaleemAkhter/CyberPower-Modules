<?php

namespace ModulesGarden\WordpressManager\App\Interfaces;

interface UserInterface
{
    public function getList();

    public function get($id);

    public function create($user);

    public function delete($id);

    public function update($user);

    public function resetPassword($id);
}
