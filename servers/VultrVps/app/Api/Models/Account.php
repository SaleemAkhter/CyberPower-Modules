<?php

namespace ModulesGarden\Servers\VultrVps\App\Api\Models;

Class Account
{

    protected  $balance; //int
    protected  $pendingCharges; //int
    protected  $name; //String
    protected  $email; //String
    protected  $acls;  //array( String )
    protected  $lastPaymentDate; //Date
    protected  $lastPaymentAmount;

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPendingCharges()
    {
        return $this->pendingCharges;
    }

    /**
     * @param mixed $pendingCharges
     * @return Account
     */
    public function setPendingCharges($pendingCharges)
    {
        $this->pendingCharges = $pendingCharges;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Account
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Account
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcls()
    {
        return $this->acls;
    }

    /**
     * @param mixed $acls
     * @return Account
     */
    public function setAcls($acls)
    {
        $this->acls = $acls;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastPaymentDate()
    {
        return $this->lastPaymentDate;
    }

    /**
     * @param mixed $lastPaymentDate
     * @return Account
     */
    public function setLastPaymentDate($lastPaymentDate)
    {
        $this->lastPaymentDate = $lastPaymentDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastPaymentAmount()
    {
        return $this->lastPaymentAmount;
    }

    /**
     * @param mixed $lastPaymentAmount
     * @return Account
     */
    public function setLastPaymentAmount($lastPaymentAmount)
    {
        $this->lastPaymentAmount = $lastPaymentAmount;
        return $this;
    }



}