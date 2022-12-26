<?php

use \ModulesGarden\Servers\AwsEc2\Core\Hook\HookIntegrator;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\di;

$hookManager->register(
    function ($args)
    {
        $hookIntegrator = new HookIntegrator($args);

        /**
         * @var $toReturn is a HTML integration code (or null if no integration was made)
         * you can add your code to this var before returning its content,
         * do not overwrite this var!
         */
        $toReturn = $hookIntegrator->getHtmlCode();

        if ($toReturn)
        {
            return $toReturn;
        }

        $request = di('request');

        if ($args['filename'] === 'configservers' && $request->get('action') === 'manage' && $request->get('id'))
        {
            return \ModulesGarden\Servers\AwsEc2\App\Helpers\ServerPageConfig::getServerPageJs();
        }
    },
    100
);
