<?php


namespace ModulesGarden\Servers\VultrVps\App\Api\Models;


class Region
{

    use AbstractObject;

    protected  $id; //String
    protected  $city; //String
    protected  $country; //String
    protected  $continent; //String
    protected  $options;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Region
     */
    public function setId($id)
    {
        $this->id = $id;
        $this->path ="regions/".$id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Region
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Region
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * @param mixed $continent
     * @return Region
     */
    public function setContinent($continent)
    {
        $this->continent = $continent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     * @return Region
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }  //array( String )


}