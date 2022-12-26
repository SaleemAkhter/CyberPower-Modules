<?php


$hookManager->register(
    function ($args)
    {
        $args['addon_modules']['OvhVpsAndDedicated'] = 'OVH VPS & Dedicated';

        return $args;
    },
    100
);
