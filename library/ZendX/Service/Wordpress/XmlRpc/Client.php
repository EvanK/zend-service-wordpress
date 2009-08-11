<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress_XmlRpc
 * @subpackage Client
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Wordpress Abstract
 * @see Zend_XmlRpc_Client
 */
require_once 'Zend/XmlRpc/Client.php';

class ZendX_Service_Wordpress_XmlRpc_Client extends Zend_XmlRpc_Client
{
    
    /**
     * XML-RPC URL for blog
     * @var _xmlRpcUrl
     */
    protected $_xmlRpcUrl;

    /**
     * Blog ID
     * @var integer _blogId
     */
    protected $_blogId;

    /**
     * Blog username
     * @var string _username
     */
    protected $_username;

    /**
     * Blog password
     * @var string _password
     */
    protected $_password;
    
    /**
     * Whether or not caching is enabled
     * @var boolean _caching
     */
    protected $_caching;

    /**
     * @var array XML-RPC results cache
     */
    protected $_cache = array();

    /**
     * @return boolean caching
     */
    public function getCaching()
    {
        return $this->_caching;
    }

    /**
     * Enable/Disable caching
     * @return ZendX_Service_Wordpress
     */
    public function setCaching($caching)
    {
        if (!$caching) {
            empty($this->_cache);
        }
        
        $this->_caching = $caching;
        
        return $this;
    }

    /**
     * Find if cache data exists for key
     * @return boolean
     */
    public function hasCacheData($key)
    {
        return isset($this->_cache[$key]);
    }

    /**
     * Retrieves cache data by key
     * @return mixed
     */
    public function getCacheData($key)
    {
        return $this->hasCacheData($key) ? $this->_cache[$key] : false;
    }

    /**
     * Sets cache data for key
     * @var string key
     * @var mixed  value
     */
    public function setCacheData($key, $value)
    {
        $this->_cache[$key] = $value;
    }

    public function getKey()
    {
        return serialize(func_get_args());
    }

    /**
     * @return Username
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return Password
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return XML-RPC URL
     */
    public function getXmlRpcUrl()
    {
        return $this->_xmlRpcUrl;
    }

    /**
     * Set XML-RPC URL
     * @return ZendX_Service_Wordpress
     */
    public function setXmlRpcUrl($xmlRpcUrl)
    {
        $this->_xmlRpcUrl = $xmlRpcUrl;
        
        return $this;
    }

    /**
     * Retrieve Wordpress username
     * @return string username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
        
        return $this;
    }

    /**
     * Retrieve Wordpress password
     * @return string password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        
        return $this;
    }

    /**
     * @return Blog ID
     */
    public function getBlogId()
    {
        return $this->_blogId;
    }

    /**
     * Retrieve current blog ID
     * @return integer blog ID
     */
    public function setBlogId($id)
    {
        $this->_blogId = $id;
        
        return $this;
    }

    /**
     * Send an XML-RPC request to the service (for a specific method)
     * Returns cached result of caching is enabled.
     *
     * @param  string $method Name of the method we want to call
     * @param  array $params Array of parameters for the method
     * @return mixed
     * @throws Zend_XmlRpc_Client_FaultException
     */
    public function call($method, $params = array())
    {
        if ($this->getCaching()) {
            $key = $this->getKey($method, $params);
            
            if (!$this->hasCacheData($key)) {
                $this->setCacheData($key, parent::call($method, $params));
            }
            
            return $this->getCacheData($key);
        } else {
            return parent::call($method, $params);
        }
    }

    /**
     * Reduce redundancy with XML-RPC calls
     *
     * @param string XML-RPC method
     * @param array  Parameters
     *
     * @return mixed XML-RPC response
     */
    public function callWithCredentials($method, $params = array())
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
        return $this->call("$service.$action", $params);
    }

}