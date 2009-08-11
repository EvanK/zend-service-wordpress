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
    
}