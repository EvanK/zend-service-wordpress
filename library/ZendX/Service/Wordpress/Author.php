<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Author
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZendX_Service_Wordpress_Abstract
 */
require_once 'ZendX/Service/Wordpress/Abstract.php';

/**
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Author
 */
class ZendX_Service_Wordpress_Author extends ZendX_Service_Wordpress_Abstract
{
    /**
     * Retrieves author id
     * @return string
     */
    public function getId() {
        return $this->get('userId');
    }
    
    /**
     * Retrieves author display name
     * @return string
     */
    public function getName() {
        return $this->get('displayName');
    }
}
