<?php

namespace ModulesGarden\Servers\AwsEc2\App\Traits;


/**
 * Breadcrumb trait
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
trait BreadcrumbComponent
{
    public function getClassMethods()
    {
        $return  = [];
        $methods = (new \ReflectionClass($this))->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach($methods as $key => $method)
        {
            if($method->class === static::class && $method->name !== __FUNCTION__)
            {
                $return[] = $method->name;
            }
        }

        return $return;
    }
}
