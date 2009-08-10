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
 * @see ZendX_Service_Wordpress
 */
require_once 'Zend/XmlRpc/Client.php';

/**
 * @see Zend_Filter_Inflector
 */
require_once 'Zend/Filter/Inflector.php';

abstract class ZendX_Service_Wordpress_Abstract extends Zend_XmlRpc_Client
{

    /**
     * XML-RPC URL for blog
     * @var _xmlRpcUrl
     */
    protected static $_xmlRpcUrl;

    /**
     * Blog ID
     * @var integer _blogId
     */
    protected static $_blogId;

    /**
     * Blog username
     * @var string _username
     */
    protected static $_username;

    /**
     * Blog password
     * @var string _password
     */
    protected static $_password;

    /**
     * Whether or not caching is enabled
     * @var boolean _caching
     */
    protected static $_caching;

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
     * Constructor
     *
     * @param  ZendX_Service_Wordpress $parent
     * @param  array $data
     * @return void
     * @throws ZendX_Service_Wordpress_Exception
     */
    public function __construct($server, Zend_Http_Client $httpClient = null)
    {
        $this->_initInflectors();
        
        parent::__construct($server, $httpClient);
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
    protected function __call($method, $params)
    {
        // Handle get<property> for members of $_data
        if (substr($method, 0, 3) == 'get') {
            $property = substr($method, 3);
            $property{0} = strtolower($property{0});
            
            return $this->get($property);
        }
        
        include_once 'ZendX/Service/Wordpress/Exception.php';
        throw new ZendX_Service_Wordpress_Exception(
            'Invalid method "' . $method . '"'
        );
    }

    /**
     * Reduce redundancy with XML-RPC calls
     *
     * @param string XML-RPC method
     * @param array  Parameters
     *
     * @return mixed XML-RPC response
     */
    public function call($method, $params = array())
    {
        // Get user's credentials
        $credentials = array(
            'username'  =>  $this->getUsername(),
            'password'  =>  $this->getPassword()
        );
        
        // Add on any additional parameters to credentials
        $params = array_merge($credentials, $params);
        
        // Identify the service (wp, metaWeblog, etc.) and the action
        list($service, $action) = explode(".", $method);
        
        // Each service identifies the "blog id" differently
        switch ($service) {
            case 'metaWeblog':
                $blog = array('blogid' => $this->getBlogId());
                break;
            default:
                $blog = array('blog_id' => $this->getBlogId());
                break;
        }
        
        // Push blog identifier onto beginning of parameters
        $params = array_merge($blog, $params);
        
        // Call requested service
        return parent::call("$service.$action", $params);
    }

    /**
     * Similar to _getCallObjects, but for a single object
     */
    protected function _getCallObject($method, $objectType, $params = array())
    {
        $data = $this->call($method, $params);
        
        // Define class name based on object type (Post, Tag, etc.)
        $className = "ZendX_Service_Wordpress_" . ucfirst($objectType);
        
        // New class uses the same XML-RPC URL & HTTP Client as Wordpress
        $object = new $className($this->getXmlRpcUrl(),
                                 $this->getHttpClient());
        $object->setData($data);
        
        return $object;
    }

    /**
     * Facilitates redundant XML-RPC calls that get converted into subclasses
     */
    protected function _getCallObjects($method, $objectType, $params = array())
    {
        $results = $this->call($method, $params);
        
        // Define class name based on object type (Post, Tag, etc.)
        $className = "ZendX_Service_Wordpress_" . ucfirst($objectType);
        
        $objects = array();
        foreach ($results as $data) {
            // New class uses the same XML-RPC URL & HTTP Client as Wordpress
            $object = new $className($this->getXmlRpcUrl(),
                                     $this->getHttpClient());
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
     * @return XML-RPC URL
     */
    public function getXmlRpcUrl()
    {
        return self::$_xmlRpcUrl;
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
     * @return Username
     */
    public function getUsername()
    {
        return self::$_username;
    }
    
    /**
     * @return Password
     */
    public function getPassword()
    {
        return self::$_password;
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

    /**
     * @return Blog ID
     */
    public function getBlogId()
    {
        return self::$_blogId;
    }

    /**
     * Enable/Disable caching
     * @return ZendX_Service_Wordpress
     */
    public function setCaching($caching)
    {
        self::$_caching = $caching;
        
        return $this;
    }

    /**
     * Set XML-RPC URL
     * @return ZendX_Service_Wordpress
     */
    public function setXmlRpcUrl($xmlRpcUrl)
    {
        self::$_xmlRpcUrl = $xmlRpcUrl;
        
        return $this;
    }

    /**
     * Retrieve Wordpress username
     * @return string username
     */
    public function setUsername($username)
    {
        self::$_username = $username;
        
        return $this;
    }

    /**
     * Retrieve Wordpress password
     * @return string password
     */
    public function setPassword($password)
    {
        self::$_password = $password;
        
        return $this;
    }

    /**
     * Retrieve current blog ID
     * @return integer blog ID
     */
    public function setBlogId($id)
    {
        self::$_blogId = $id;
    }
    

}
