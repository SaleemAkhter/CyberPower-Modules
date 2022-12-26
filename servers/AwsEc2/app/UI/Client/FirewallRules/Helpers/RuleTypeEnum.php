<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers;


use ReflectionClass;

class RuleTypeEnum
{
    const CUSTOM_PROTOCOL   = ['customprotocol', 'Custom Protocol'];
    const CUSTOM_TCP        = ['customtcp', 'Custom TCP'];
    const CUSTOM_UDP        = ['customudp', 'Custom UDP'];
    const CUSTOM_ICMPV6     = ['customicmpv6', 'Custom ICMP - IPv6'];
    const CUSTOM_ICMPV4     = ['customicmpv4', 'Custom ICMP - IPv4'];
    const ALL_TCP           = ['alltcp', 'All TCP'];
    const ALL_UDP           = ['alludp', 'All UDP'];
    const ALL_ICMPV6        = ['allicmpv6', 'All ICMP - IPv6'];
    const ALL_ICMPV4        = ['allicmpv4', 'All ICMP - IPv4'];
    const ALL_TRAFFIC       = ['alltraffic', 'All Traffic'];
    const SSH               = ['ssh', 'SSH'];
    const SMTP              = ['smtp', 'SMTP'];
    const DNS_TCP           = ['dnstcp', 'DNS TCP'];
    const DNS_UDP           = ['dnsudp', 'DNS UDP'];
    const HTTP              = ['http', 'HTTP'];
    const POP3              = ['pop3', 'POP3'];
    const IMAP              = ['imap', 'IMAP'];
    const LDAP              = ['ldap', 'LDAP'];
    const HTTPS             = ['https', 'HTTPS'];
    const SMB               = ['smb', 'SMB'];
    const SMTPS             = ['smtps', 'SMTPS'];
    const IMAPS             = ['imaps', 'IMAPS'];
    const POP3S             = ['pop3s', 'POP3S'];
    const MSSQL             = ['mssql', 'MSSQL'];
    const NFS               = ['nfs', 'NFS'];
    const MYSQL_AURORA      = ['mysqlaurora', 'MySQL Aurora'];
    const RDP               = ['rdp', 'RDP'];
    const REDSHIFT          = ['redshift', 'Redshift'];
    const POSTGRESQL        = ['posgresql', 'PostgreSQL'];
    const ORACLE_RDS        = ['oraclerds', 'Oracle RDS'];
    const WINRM_HTTP        = ['windrmhttp', 'WINRM HTTP'];
    const WINRM_HTTPS       = ['winrmhttps', 'WINRM HTTPS'];
    const ELASTIC_GRAPHICS  = ['elasticgraphics', 'Elastic Grapthics'];
}