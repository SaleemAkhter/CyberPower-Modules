<?php

namespace ModulesGarden\WordpressManager\App\Helper;

use ModulesGarden\WordpressManager\App\Models\Installation;

class InstallationLimit
{
    public function limitReached($hostingId, $limit)
    {
        $installationsAmount = count(Installation::where('hosting_id', $hostingId)->pluck('id'));

        if((int)$limit == $limit && (int)$limit > 0)
        {
            if ($installationsAmount >= $limit)
            {
                return true;
            }
        }

        return false;
    }

    public function stageCheckLimit($wpid)
    {
        $installation = Installation::find($wpid);
        $limit = $installation->hosting->productSettings()->first()->getInstallationsLimit();
        return InstallationLimit::limitReached($installation->hosting_id, $limit);
    }
}
