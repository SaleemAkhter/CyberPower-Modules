<?php

namespace ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\Helpers;

class Images
{
    const FILTERS = [
        'searchName'   => [
            'name'      => 'name',
            'wildcard'  => true
        ],
        'architecture' => [
            'name' => 'architecture',
            'allowedValues' => [
                'x86_64',
                'i386',
                'arm64'
            ],
            'defaultValue' => 'x86_64'
        ],
        'imageType' => [
            'name' => 'image-type',
            'allowedValues' => [
                'machine',
                'kernel',
                'ramdisk'
            ],
            'defaultValue' => 'machine'
        ],
        'platform' => [
            'name' => 'platform',
            'allowedValues' => [
                'windows'
            ]
        ],
        'virtualizationType' => [
            'name' => 'virtualization-type',
            'allowedValues' => [
                'hvm',
                'paravirtual'
            ],
            'defaultValue' => 'hvm'
        ],
        'ownerAlias' => [
            'name' => 'owner-alias',
            'allowedValues' => [
                'amazon',
                'aws-marketplace',
                'microsoft'
            ],
            'defaultValue' => 'amazon'
        ]
    ];

    public function prepareFiltersData($params = [])
    {
        $filters = [];
        foreach($params as $name => $value)
        {
            if (!self::FILTERS[$name])
            {
                continue;
            }

            if(!empty(self::FILTERS[$name]['allowedValues']) && !in_array($value, self::FILTERS[$name]['allowedValues']))
            {
                continue;
            }

            if(!empty(self::FILTERS[$name]['wildcard']) && self::FILTERS[$name]['wildcard'] == true)
            {
                $value  = '*'.trim($value).'*';
            }

            $filters[] = [
                'Name' => self::FILTERS[$name]['name'],
                'Values' => [$value]
            ];
        }

        $filters[] = [
                'Name' => 'state',
                'Values' => ['available']
            ];

        return $filters;
    }
}