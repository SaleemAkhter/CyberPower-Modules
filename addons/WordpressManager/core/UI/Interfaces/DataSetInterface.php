<?php

namespace ModulesGarden\WordpressManager\Core\UI\Interfaces;

/* 
 * Interface for set of data returned by datatable
 */
interface DataSetInterface
{

    public function getOffset();

    public function getRecords();

    public function getLenght();

    public function getFullLenght();
}
