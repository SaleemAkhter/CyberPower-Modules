<?php



class soapclientd extends soapclient
{



    public $action = false;



    public function __construct($wsdl, $options = array())
    {

        parent::__construct($wsdl, $options);
    }



    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {

        $resp = parent::__doRequest($request, $location, $action, $version, $one_way);

        return $resp;
    }
}
