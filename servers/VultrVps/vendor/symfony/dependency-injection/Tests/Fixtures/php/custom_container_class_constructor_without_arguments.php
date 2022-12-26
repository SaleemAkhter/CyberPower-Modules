<?php

namespace Symfony\Component\DependencyInjection\Tests\Fixtures\Container;

use Symfony\Component\DependencyInjection\Exception\LogicException;

/**
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * @final since Symfony 3.3
 */
class ProjectServiceContainer extends \Symfony\Component\DependencyInjection\Tests\Fixtures\Container\ConstructorWithoutArgumentsContainer
{
    private $parameters = [];
    private $targetDirs = [];

    public function __construct()
    {
        parent::__construct();
        $this->parameterBag = null;

        $this->services = [];

        $this->aliases = [];
    }

    public function getRemovedIds()
    {
        return [
            'Psr\\Container\\ContainerInterface' => true,
            'Symfony\\Component\\DependencyInjection\\ContainerInterface' => true,
        ];
    }

    public function compile()
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    public function isCompiled()
    {
        return true;
    }

    public function isFrozen()
    {
        @trigger_error(sprintf('The %s() method is deprecated since Symfony 3.3 and will be removed in 4.0. Use the isCompiled() method instead.', __METHOD__), E_USER_DEPRECATED);

        return true;
    }
}
