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

    /**
     * @return post ID
     */
    public function getId()
    {
        return $this->get('postid');
    }

    /**
     * @return post title
     */
    public function getTitle()
    {
        return $this->get('title');
    }

    /**
     * return post url
     */
    public function getUrl()
    {
        return $this->get('link');
    }

    /**
     * @return post permalink-url
     */
    public function getPermaUrl()
    {
        return $this->get('permaLink');
    }

    /**
     * @return link to post's permaurl
     */
    public function getPermaLink()
    {
        return $this->getLink($this->getPermaUrl());
    }

    /**
     * @return array post categories
     */
    public function getCategories()
    {
        // Get category objects that are in post
        $categoryObjects = array_filter(
            parent::getCategories(),
            array($this, "hasCategory")
        );
        
        // Sort category objects to same order as post categories
        $categoryNames = $this->get('categories');
        foreach ($categoryObjects as $categoryObject) {
            $index = array_search(
                $categoryObject->getName(),
                $categoryNames
            );
            
            $categoryNames[$index] = $categoryObject;
        }
        
        return $categoryNames;
    }

    /**
     * @return boolean whether or not post has specified category
     */
    public function hasCategory($category)
    {
        if ($category instanceof ZendX_Service_Wordpress_Blog_Category) {
            $category = $category->getName();
        }
        
        return in_array($category, $this->get('categories'));
    }

    /**
     * Post returns body content when cast as string
     * @return string post content
     */
    public function __toString()
    {
        return $this->getDescription();
    }

}
