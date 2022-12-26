<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Firewall as FirewallEntity;

/**
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Firewall extends AbstractApi
{
    /**
     * @param array $criteria
     *
     * @return FirewallEntity[]
     */
    public function getAll()
    {
        $query = sprintf('%s/firewalls', $this->endpoint);

        $firewalls = $this->adapter->get($query);

        $firewalls = json_decode($firewalls);

        $this->extractMeta($firewalls);

        return array_map(function ($firewalls) {
            return new FirewallEntity($firewalls);
        }, $firewalls->firewalls);
    }

    public function getById($id){
        $firewalls = $this->adapter->get(sprintf('%s/firewalls/', $this->endpoint). $id);
        $firewalls = json_decode($firewalls);

        return new FirewallEntity($firewalls->firewall);
    }

    public function delete($id)
    {
        $this->adapter->delete(sprintf('%s/firewalls/', $this->endpoint). $id);
    }

    public function deleteRule($id, $ruleData)
    {
        $this->adapter->delete(sprintf('%s/firewalls/', $this->endpoint). $id . '/rules', $ruleData);
    }
    public function addRule($id, $type, $protocol, $ports, $sources = [])
    {
        $data[$type][] =[
            'protocol'                                             => $protocol,
            'ports'                                                => $ports,
            ($type == 'inbound_rules') ? 'sources': 'destinations' => $sources,
        ];
        $firewalls = $this->adapter->post(sprintf('%s/firewalls/', $this->endpoint). $id . '/rules', $data);

        $firewalls = json_decode($firewalls);

        return new FirewallEntity($firewalls->firewall);

    }
    public function create($name, $inbound_rules = [], $outbound_rules = [], $droplet_ids =[], $tags =[])
    {
        $data =[
            'name' => $name,
            'inbound_rules' => $inbound_rules,
            'outbound_rules' => $outbound_rules,
            'droplet_ids' => $droplet_ids,
            'tags' => $tags
        ];
        $firewalls = $this->adapter->post(sprintf('%s/firewalls', $this->endpoint), $data);

        $firewalls = json_decode($firewalls);

        return new FirewallEntity($firewalls->firewall);

    }

    public function edit( $firewallId, $firewallName,$droplet_ids, $inboundRules, $outboundRules )
    {
        $data      = [
            'name'           => $firewallName,
            'droplet_ids'    => $droplet_ids,
            'inbound_rules'  => $inboundRules,
            'outbound_rules' => $outboundRules
        ];

        $firewalls = $this->adapter->put(sprintf('%s/firewalls/', $this->endpoint) . $firewallId, $data);

        $firewalls = json_decode($firewalls);

        return new FirewallEntity($firewalls->firewall);
    }


}
