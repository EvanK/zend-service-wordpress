<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress_Blog
 * @subpackage Post
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZendX_Service_Wordpress_Abstract
 */
require_once 'ZendX/Service/Wordpress/Abstract.php';

class ZendX_Service_Wordpress_Blog_Category extends ZendX_Service_Wordpress_Blog
{
    public function getId()
    {
        return $this->get('categoryId');
    }
    
    public function getParentId()
    {
        return $this->get('categoryParentId');
    }
    
    public function getName()
    {
        return $this->get('categoryName');
    }
    
    public function getDescription()
    {
        return $this->get('categoryDescription');
    }
    
    public function __toString()
    {
        return $this->getName();
    }
}