<?php

namespace LKDev\HetznerCloud\Models\Firewalls;

/**
 * Class FirewallRule.
 */
class FirewallRule
{
    const DIRECTION_IN = 'in';

    const DIRECTION_OUT = 'out';

    const PROTOCOL_TCP = 'tcp';

    const PROTOCOL_UDP = 'udp';

    const PROTOCOL_ICMP = 'icmp';
    /**
     * @var string
     */
    public $direction;
    /**
     * @var array<string>
     */
    public $sourceIPs;
    /**
     * @var array<string>
     */
    public $destinationIPs;
    /**
     * @var string
     */
    public $protocol;
    /**
     * @var string
     */
    public $port;

    /**
     * FirewallRule constructor.
     * @param string $direction
     * Possible enum values:
     * <b>in</b>/<b>out</b><br>
     * Select traffic direction on which rule should be applied. Use <b>source_ips</b> for direction <b>in</b><br>and <b>destination_ips</b> for direction <b>out</b>.<br>
     * @param string $protocol
     * Type of traffic to allow<br>
     * Possible enum values:
     * <b>tcp</b>/<b>udp</b>/<b>icmp</b>
     * @param string[] $sourceIPs
     * List of permitted IPv4/IPv6 addresses in CIDR notation.<br>
     * Use <b>0.0.0.0/0</b> to allow all IPv4 addresses and <b>::/0</b> to allow all IPv6 addresses.<br>
     * You can specify 100 CIDRs at most.
     * @param string[] $destinationIPs
     * List of permitted IPv4/IPv6 addresses in CIDR notation.<br>
     * Use <b>0.0.0.0/0</b> to allow all IPv4 addresses and <b>::/0</b> to allow all IPv6 addresses.<br>
     * You can specify 100 CIDRs at most.
     * @param string $port
     * Port or port range to which traffic will be allowed, only applicable for protocols TCP and UDP.<br>
     * A port range can be specified by separating two ports with a dash, e.g <b>1024-5000</b>.
     */
    public function __construct(string $direction, string $protocol, array $sourceIPs = [], array $destinationIPs = [], $port = '')
    {
        $this->direction = $direction;
        $this->sourceIPs = $sourceIPs;
        $this->destinationIPs = $destinationIPs;
        $this->protocol = $protocol;
        $this->port = $port;
    }

    /**
     * @return array
     */
    public function toRequestSchema(): array
    {
        $s = [
            'direction' => $this->direction,
            'source_ips' => $this->sourceIPs,
            'protocol' => $this->protocol,
        ];
        if (! empty($this->destinationIPs)) {
            $s['destination_ips'] = $this->destinationIPs;
        }
        if ($this->port != '') {
            $s['port'] = $this->port;
        }

        return $s;
    }
}
