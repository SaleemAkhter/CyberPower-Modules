<?php

use function \ModulesGarden\Servers\AwsEc2\Core\Helper\di;

$hookManager->register(
    function ($args)
    {
        $appParams = di("appParamsContainer");
        $moduleName = $appParams->getParam('systemName');

        if ($args['modulename'] === $moduleName || $args['modulename'] === $moduleName)
        {
            $sid = $args['serviceid'];
            $request = di('request');
            $requestUrl = $request->server->get('REQUEST_URI');

            $decodedUrl = htmlspecialchars_decode(urldecode($requestUrl));
            if (!stripos($decodedUrl, '&id='))
            {
                header('Location: ' . $decodedUrl . '&id=' . $sid);
                exit();
            }
        }

    },
    100
);
