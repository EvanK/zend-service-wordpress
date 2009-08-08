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
        $camelCase->setRules(array(
            ':key'  => array('Word_CamelCaseToUnderscore', 'StringToLower')
        ));
        
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
        foreach ($data as $key => $value) {
            $normal_key = $this->_normalize($key);
            
            unset($data[$key]);
            $data[$normal_key] = $value;
        }
        
        $this->_data = $data;
        
        return $this;
    }
    
    /**
     * Normalize keys returned by XML-RPC
     * ("mt_blog_id" becomes "blogId")
     * 
     * @return string $key
     */
    protected function _normalize($key)
    {
        $normal_key = preg_replace('/[^a-z0-9]+/i', '',
            preg_replace_callback('/_+([a-z])/i',
                create_function('$matches', 'return strtoupper($matches[1]);'),
                $key
            )
        );
        
        return $key;
    }
    
    /**
     * Method overloading
     *
     * @param  string $method
     * @param  array $params
     * @return mixed
     * @throws ZendX_Service_Wordpress_Exception if unable to find method
     */
    protected function __call($method, $params) {
        // Handle get<property> for members of $_data
        if (substr($method, 0, 3) == 'get') {
            $property = substr($method, 3);
            $property{0} = strtolower($property{0});
            
            return $this->get($property);
        }
        
        include_once 'ZendX/Service/Wordpress/Exception.php';
        throw new ZendX_Service_Wordpress_Exception('Invalid method "' . $method . '"');
    }
    
    
    /**
     * Retrieve data set by key
     * @param  string (optional) $key
     * @return mixed
     * @throws Zend_Service_Exception if unable to find key
     */
    public function get($key) {
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
        throw new ZendX_Service_Wordpress_Exception('Key "' . $key . '" not set in data.');
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
        
        return sprintf('<a href="%s" title="%s">%s</a>', $url,
                                                         $this->getTitle(),
                                                         $this->getTitle());
    }
}
