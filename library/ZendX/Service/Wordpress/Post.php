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
    /**
     * Retrieves post id
     * @return string
     */
    public function getId() {
        return $this->get('postid');
    }
    
    /**
     * Retrieves post slug
     * @return string
     */
    public function getSlug() {
        return $this->get('wpSlug');
    }
    
    /**
     * Retrieves author information
     * @return ZendX_Service_Wordpress_Author
     */
    public function getAuthor() {} # @TODO: implement Author class
    
    /**
     * Retrieves all categories to which the post is assigned
     * @return array
     */
    public function getCategories() {} # @TODO: implement Category class
    
    /**
     * Retrieves all tags assigned to the post
     * @return array
     */
    public function getTags() {} # @TODO: implement Tag class
}
