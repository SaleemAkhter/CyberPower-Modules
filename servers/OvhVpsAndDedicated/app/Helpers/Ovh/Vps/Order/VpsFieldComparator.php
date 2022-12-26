<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Order;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Vps as VpsModel;

/**
 * Class Comparator
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class VpsFieldComparator
{
    CONST PRODUCT_PLAN_PREFFIX = 'vps';
    CONST SSD_PRODUCT_PLAN_SUFFIX = 'ssd';
    CONST CLOUD_PRODUCT_PLAN_SUFFIX = 'ceph-nvme';
    CONST CLOUDRAM_PRODUCT_PLAN_SUFFIX = 'ceph-nvme-ram';

    /**
     * @param VpsModel $vpsConfig
     * @return string
     * @deprecated
     */
    public static function prepareProductPlan(VpsModel $vpsConfig)
    {
        $submodel        = $vpsConfig->getSubModel();
        $fullProductName = self::getFrontFormattedPlan($vpsConfig->getOfferType()) . "_{$submodel->getName()}_{$submodel->getVersion()}";
        return $fullProductName;
    }


    public static function prepareLocalization(VpsModel $vpsConfig)
    {
        return $vpsConfig->sameDataCenter['name'];
    }


    public static function getFrontFormattedPlan($type)
    {
        $toFormat = [
            'ssd'      => SELF::PRODUCT_PLAN_PREFFIX . '_' . SELF::SSD_PRODUCT_PLAN_SUFFIX,
            'cloud'    => SELF::PRODUCT_PLAN_PREFFIX . '_' . SELF::CLOUD_PRODUCT_PLAN_SUFFIX,
            'cloudram' => SELF::PRODUCT_PLAN_PREFFIX . '_' . SELF::CLOUDRAM_PRODUCT_PLAN_SUFFIX,
        ];
        //TODO module log if not exists
        return isset($toFormat[$type]) ? $toFormat[$type] : $type;
    }
}