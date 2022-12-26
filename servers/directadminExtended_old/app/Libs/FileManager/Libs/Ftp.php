<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Libs;

class Ftp
{
    private $host;
    private $username;
    private $password;
    private $connection;
    private $login;

    public function __construct($username, $password, $host)
    {
        $this->username = $username;
        $this->password = $password;
        $this->host     = $host;
    }

    private function connect()
    {
        $this->connection = ftp_connect($this->host);
        if (!$this->connection)
        {
            throw new \Exception('Something went wrong with ftp connection.');
        }
    }

    private function login()
    {
        $this->login = ftp_login($this->connection, $this->username, $this->password);
        if (!$this->login)
        {
            throw new \Exception('Something went wrong with ftp auth.');
        }
    }
    
    private function passiveMode(boolean $mode)
    {
        ftp_pasv($this->connection, $mode);
    }

    private function put($path,$file)
    {
        if(empty($path))
        {
            $path = $file;
        }
        else
        {
            $path = $path . '/' . $file;
        }
        $put = ftp_put($this->connection, $path, $file, FTP_BINARY);
        if(!$put)
        {
            
        }
    }

    private function close()
    {
        ftp_close($this->connection);
    }

    public function upload(array $data)
    {
        $file = $data['file'];
        $path = $data['path'];
        
        $this->connect();
        $this->login();
        $this->passiveMode(true);
        $this->put($path,$file);
        $this->close();
        
        return true;
    }
}
