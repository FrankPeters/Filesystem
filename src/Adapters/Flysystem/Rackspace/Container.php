<?php

namespace Mosaic\Filesystem\Adapters\Flysystem\Rackspace;

class Container
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $objectStoreName;

    /**
     * @var string
     */
    private $objectStoreLocation;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param string $name
     * @param string $endpoint
     * @param string $username
     * @param string $password
     */
    public function __construct(string $name, string $endpoint, string $username, string $password)
    {
        $this->setName($name);
        $this->setEndpoint($endpoint);
        $this->setUsername($username);
        $this->setPassword($password);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param  string $endpoint
     * @return $this
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param  string $username
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param  string $password
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getObjectStoreName()
    {
        return $this->objectStoreName;
    }

    /**
     * @param  string $objectStoreName
     * @return $this
     */
    public function setObjectStoreName(string $objectStoreName)
    {
        $this->objectStoreName = $objectStoreName;

        return $this;
    }

    /**
     * @return string
     */
    public function getObjectStoreLocation()
    {
        return $this->objectStoreLocation;
    }

    /**
     * @param  string $objectStoreLocation
     * @return $this
     */
    public function setObjectStoreLocation(string $objectStoreLocation)
    {
        $this->objectStoreLocation = $objectStoreLocation;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param  array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }
}
