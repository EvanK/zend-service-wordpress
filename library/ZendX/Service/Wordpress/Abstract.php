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
require_once 'ZendX/Service/Wordpress.php';

/**
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Abstract
 */
abstract class ZendX_Service_Wordpress_Abstract
{
    /**
     * Parent Wordpress object
     * @var ZendX_Service_Wordpress
     */
    protected $_parent;

    /**
     * Object data
     * @var array
     */
    protected $_data;

    /**
     * Constructor
     *
     * @param  ZendX_Service_Wordpress $parent
     * @param  array $data
     * @return void
     * @throws Zend_Service_Exception
     */
    public function __construct($parent, $data)
    {
        // Force parent to be Wordpress object
        if(!($parent instanceof ZendX_Service_Wordpress)) {
            require_once 'Zend/Service/Exception.php';
            throw new Zend_Service_Exception('Parent is not a ZendX_Service_Wordpress object');
        }
        $this->_parent = $parent;
        
        // Normalize data keys
        $this->_data = array();
        foreach($data as $key => $item) {
            $normal_key = preg_replace('/[^a-z0-9]+/i', '',
                preg_replace_callback('/_+([a-z])/i',
                    create_function('$matches', 'return strtoupper($matches[1]);'),
                    $key
                )
            );
            $this->_data[$normal_key] = $item;
        }
    }

    /**
     * Method overloading
     *
     * @param  string $method
     * @param  array $params
     * @return mixed
     * @throws Zend_Service_Exception if unable to find method
     */
    protected function __call($method, $params) {
        // Handle get<property> for members of $_data
        if(substr($method, 0, 3) == 'get') {
            $property = substr($method, 3);
            $property{0} = strtolower($property{0});
            return $this->get($property);
        }
        
        require_once 'Zend/Service/Exception.php';
        throw new Zend_Service_Exception('Invalid method "' . $method . '"');
    }
    
    /**
     * @param  string $property
     * @return mixed
     * @throws Zend_Service_Exception if unable to find property
     */
    public function get($property) {
        if(isset($this->_data[$property])) {
            return $this->_data[$property];
        }
        require_once 'Zend/Service/Exception.php';
        throw new Zend_Service_Exception('Invalid property "' . $property . '"');
    }
}
