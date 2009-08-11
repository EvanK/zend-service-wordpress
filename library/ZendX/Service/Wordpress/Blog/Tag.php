<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress_Blog
 * @subpackage Tag
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZendX_Service_Wordpress_Abstract
 */
require_once 'ZendX/Service/Wordpress/Abstract.php';

class ZendX_Service_Wordpress_Blog_Tag extends ZendX_Service_Wordpress_Blog
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