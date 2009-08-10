<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Blog
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZendX_Service_Wordpress_Abstract
 */
require_once 'ZendX/Service/Wordpress/Abstract.php';

/**
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Blog
 */
class ZendX_Service_Wordpress_Blog extends ZendX_Service_Wordpress_Abstract
{

    /**
     * Override default setData method, since wp.getOptions returns
     * associative data instead of key/value pairs
     */
    public function setData($data = array())
    {
        foreach ($data as $key => $option) {
            $data[$key] = $option['value'];
        }
        
        return parent::setData($data);
    }

    /**
     * @return Blog title
     */
    public function getTitle()
    {
        return $this->get('blog_title');
    }

    /**
     * @return Blog tagline
     */
    public function getTagline()
    {
        return $this->get('blog_tagline');
    }

    /**
     * @return Blog URL
     */
    public function getUrl()
    {
        return $this->get('blog_url');
    }

    /**
     * Return recent posts
     * @var integer (Defaults to 10) limit
     */
    public function getRecentPosts($limit = 10)
    {
        return $this->_getCallObjects(
            'metaWeblog.getRecentPosts',
            'post',
            array(
                'numberOfPosts' =>  $limit
            )
        );
    }

    /**
     * Return all of the authors on the site
     * @return array ZendX_Service_Wordpress_Author
     */
    public function getAuthors()
    {
        return $this->_getCallObjects('wp.getAuthors', 'author');
    }

    /**
     * Return all of the categories on the site
     * @return array ZendX_Service_Wordpress_Categories
     */
    public function getCategories()
    {
        return $this->_getCallObjects('wp.getCategories', 'category');
    }

    /**
     * Return all of the tags on the site
     * @return array ZendX_Service_Wordpress_Tag
     */
    public function getTags()
    {
        return $this->_getCallObjects('wp.getTags', 'tag');
    }

}