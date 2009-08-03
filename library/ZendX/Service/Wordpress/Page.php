<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Page
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZendX_Service_Wordpress_Abstract
 */
require_once 'ZendX/Service/Wordpress/Abstract.php';

/**
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Page
 */
class ZendX_Service_Wordpress_Page extends ZendX_Service_Wordpress_Abstract
{
    /**
     * Retrieves page id
     * @return string
     */
    public function getId() {
        return $this->get('pageId');
    }
    
    /**
     * Retrieves author display name
     * @return string
     */
    public function getSlug() {
        return $this->get('wpSlug');
    }
    
    /**
     * Retrieves author information
     * @return ZendX_Service_Wordpress_Author
     */
    public function getAuthor() {
        return $this->parent->getAuthor( $this->get('wpAuthorId') );
    }
    
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
