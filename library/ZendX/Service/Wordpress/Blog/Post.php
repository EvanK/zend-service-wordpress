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

class ZendX_Service_Wordpress_Blog_Post extends ZendX_Service_Wordpress_Blog
{

    public function getId()
    {
        return $this->get('postid');
    }
    
    public function getTitle()
    {
        return $this->get('title');
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
