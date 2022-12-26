<?php
namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers;

class SecurityRulesConstants
{
    const PROTOCOL_UDP = 'UDP';
    const PROTOCOL_TCP = 'TCP';
    const PROTOCOL_ICMPV6 = 'ICMP IPv6';
    const PROTOCOL_ICMP = 'ICMP';
    const PROTOCOL_ALL = 'All';

    const TABLE_PORTS_SEPARATOR = '-';

    const RULE_INBOUND = 'Inbound';
    const RULE_OUTBOUND = 'Outbound';

    const ALL_TCP_UDP_PORTS = [0, 65535];

    const IPV6_PREFIX = 'Ipv6';
    const IPV_PREFIX = 'Ip';


    public static function getProtocols()
    {
        return array(
            self::PROTOCOL_UDP => self::PROTOCOL_UDP,
            self::PROTOCOL_TCP => self::PROTOCOL_TCP,
            self::PROTOCOL_ICMPV6 => self::PROTOCOL_ICMPV6,
            self::PROTOCOL_ICMP => self::PROTOCOL_ICMP,
            self::PROTOCOL_ALL => self::PROTOCOL_ALL
        );
    }

    public static function getRuleDirections()
    {
        return array(
            self::RULE_INBOUND => self::RULE_INBOUND,
            self::RULE_OUTBOUND => self::RULE_OUTBOUND
        );
    }
}