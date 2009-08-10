<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Tag
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZendX_Service_Wordpress_Abstract
 */
require_once 'ZendX/Service/Wordpress/Abstract.php';

/**
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Post
 */
class ZendX_Service_Wordpress_Tag extends ZendX_Service_Wordpress_Abstract
{

    public function getId()
    {
        return $this->get('tag_id');
    }
    
    public function __toString()
    {
        return $this->getName();
    }
}