<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Abstract
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZendX_Service_Wordpress_XmlRpc_Client
 */
require_once 'ZendX/Service/Wordpress/XmlRpc/Client.php';

/**
 * @see Zend_Filter_Inflector
 */
require_once 'Zend/Filter/Inflector.php';

abstract class ZendX_Service_Wordpress_Abstract
{

    /**
     * XML-RPC Client
     * @var Zend_XmlRpc_Client
     */
    protected $_xmlRpcClient;

    /**
     * Object data
     * @var array
     */
    protected $_data;

    /**
     * Inflectors to assist in pulling data by key
     */
    protected $_inflectors = array();

    /**
     * Constructor to initialize inflectors for getting data
     *
     * @return void
     */
    public function __construct(ZendX_Service_Wordpress_XmlRpc_Client $client=null)
    {
        if (null !== $client) {
            $this->setXmlRpcClient($client);
        }
        
        $this->_initInflectors();
    }

    protected function _initInflectors()
    {
        $camelCase = new Zend_Filter_Inflector(':key');
        
        $camelCase->setRules(
            array(':key' => array('Word_CamelCaseToUnderscore','StringToLower'))
        );
        
        array_push($this->_inflectors, $camelCase);
    }

    /**
     * Store the data returned by XML-RPC request
     *
     * @param  array $data
     * @return ZendX_Service_Wordpress_Abstract
     */
    public function setData($data = array())
    {
        $this->_data = $data;
        
        return $this;
    }

    /**
     * Method overloading
     *
     * @param  string $method
     * @param  array $params
     * @return mixed
     * @throws ZendX_Service_Wordpress_Exception if unable to find method
     */
    public function __call($method, $params)
    {
        // Handle get<property> for members of $_data
        if (substr($method, 0, 3) == 'get') {
            $property = lcfirst(substr($method, 3));
            
            return $this->get($property);
        }
        
        include_once 'ZendX/Service/Wordpress/Exception.php';
        throw new ZendX_Service_Wordpress_Exception(
            'Invalid method "' . $method . '"'
        );
    }

    /**
     * Facilitates redundant XML-RPC calls that get converted into subclasses
     */
    protected function _getCallObjects($method, $objectType, $params = array())
    {
        // Get results from authenticated method call
        $client = $this->getXmlRpcClient();
        $results = $client->callWithCredentials($method, $params);
        
        // Define class name based on object type (Post, Tag, etc.)
        $className = "ZendX_Service_Wordpress_Blog_" . ucfirst($objectType);
        
        $objects = array();
        foreach ($results as $data) {
            // Instantiate the new class using the existing client
            $object = new $className($client);
            $object->setData($data);
            
            array_push($objects, $object);
        }
        
        return $objects;
    }

    /**
     * Retrieve data set by key
     * @param  string (optional) $key
     * @return mixed
     * @throws Zend_Service_Exception if unable to find key
     */
    public function get($key)
    {
        $keys = array($key);
        
        // Inflect key for possible matches (eg. BlogTitle => blog_title)
        foreach ($this->_inflectors as $inflector) {
            $property = $inflector->filter(array('key' => $key));
            
            array_push($keys, $property);
        }
        
        foreach ($keys as $key) {
            if (isset($this->_data[$key])) {
                return $this->_data[$key];
            }
        }
        
        include_once 'ZendX/Service/Wordpress/Exception.php';
        throw new ZendX_Service_Wordpress_Exception(
            'Key "' . $key . '" not set in data.'
        );
    }

    /**
     * @return ZendX_Service_Wordpress_XmlRpc_Client XML-RPC Client
     */
    public function getXmlRpcClient($xmlRpcUrl = null)
    {
        if (null == $this->_xmlRpcClient || isset($xmlRpcUrl)) {
            $client = new ZendX_Service_Wordpress_XmlRpc_Client($xmlRpcUrl);
            
            $this->setXmlRpcClient($client);
        }
        
        return $this->_xmlRpcClient;
    }

    /**
     * @param ZendX_Service_Wordpress_XmlRpc_Client XML-RPC client
     */
    public function setXmlRpcClient(ZendX_Service_Wordpress_XmlRpc_Client $client)
    {
        $this->_xmlRpcClient = $client;
        return $this;
    }

    /**
     * Retrieve all data
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @return XHTML link to the object
     */
    public function getLink($url = null)
    {
        if (null === $url) {
            $url = $this->getUrl();
        }
        
        return sprintf(
            '<a href="%s" title="%s">%s</a>',
            $url,
            $this->getTitle(),
            $this->getTitle()
        );
    }

}
