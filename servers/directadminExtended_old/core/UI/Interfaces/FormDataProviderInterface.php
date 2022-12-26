<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces;

/**
 * FormDataProviderInterface Interface
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
interface FormDataProviderInterface
{

    public function create();

    public function read();

    public function update();

    public function delete();

    public function getValueById($id);
}
