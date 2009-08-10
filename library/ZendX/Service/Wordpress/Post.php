<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Post
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
class ZendX_Service_Wordpress_Post extends ZendX_Service_Wordpress_Abstract
{

    public function getId()
    {
        return $this->get('postid');
    }
    
    public function getUrl()
    {
        return $this->get('link');
    }
    
    public function getPermaUrl()
    {
        return $this->get('permaLink');
    }
    
    public function getPermaLink()
    {
        return $this->getLink($this->getPermaUrl());
    }
    
    public function __toString()
    {
        return $this->getDescription();
    }

}
