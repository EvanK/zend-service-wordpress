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
 * XML-RPC Client
 * @see Zend_XmlRpc_Client
 */
require_once 'Zend/XmlRpc/Client.php';

/**
 * Wordpress authors
 * @see ZendX_Service_Wordpress_Author
 */
require_once 'ZendX/Service/Wordpress/Blog/Author.php';

/**
 * Wordpress posts
 * @see ZendX_Service_Wordpress_Post
 */
require_once 'ZendX/Service/Wordpress/Blog/Post.php';

/**
 * Wordpress categories
 * @see ZendX_Service_Wordpress_Category
 */
require_once 'ZendX/Service/Wordpress/Blog/Category.php';

/**
 * Wordpress tags
 * @see ZendX_Service_Wordpress_Tag
 */
require_once 'ZendX/Service/Wordpress/Blog/Tag.php';

class ZendX_Service_Wordpress_Blog extends ZendX_Service_Wordpress_Abstract
{

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
     * Return all of the posts on the site
     * @return array ZendX_Service_Wordpress_Blog_Post
     */
    public function getPosts() {
        return $this->_getCallObjects(
            'metaWeblog.getRecentPosts',
            'post',
            array(
                'numberOfPosts' => 65536
            )
        );
    }

    /**
     * Return a specific post by slug
     * @param string $slug
     * @return ZendX_Service_Wordpress_Blog_Post
     */
    public function getPost($slug) {
        $posts = $this->getPosts();
        
        foreach ($posts as $post) {
            if ($slug === $post->getSlug()) {
                return $post;
            }
        }
        
        return false;
    }

    /**
     * Return recent posts
     * @param integer $limit Defaults to 10
     * @param integer $start Defaults to 0
     */
    public function getRecentPosts($limit = 10, $start = 0)
    {
        $posts = $this->getPosts();
        return array_splice($posts, $start, $limit);
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
     * @return array ZendX_Service_Wordpress_Category
     */
    public function getCategories()
    {
        return $this->_getCallObjects('wp.getCategories', 'category');
    }

    /**
     * Return a specific category by name
     * return ZendX_Service_Wordpress_Category category
     */
    public function getCategory($name)
    {
        $categories = $this->getCategories();
        
        foreach ($categories as $category) {
            if ($name === $category->getName()) {
                return $category;
            }
        }
        
        return false;
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