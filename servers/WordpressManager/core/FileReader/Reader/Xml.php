<?php

namespace ModulesGarden\WordpressManager\Core\FileReader\Reader;

use ModulesGarden\WordpressManager\Core\ServiceLocator;

/**
 * Description of Xml
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Xml extends AbstractType
{

    protected function loadFile()
    {

        // https://packagist.org/packages/sabre/xml

        $this->data = [];
        ServiceLocator::call('errorManager')->addError(self::class, "First install composer sabre/xml", ['url' => 'https://packagist.org/packages/sabre/xml']);
    }
}
