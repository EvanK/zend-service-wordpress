<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service
 * @subpackage Wordpress
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * XML-RPC Client
 * @see Zend_XmlRpc_Client
 */
require_once 'Zend/XmlRpc/Client.php';

/**
 * Wordpress Abstract
 * @see ZendX_Service_Wordpress_Abstract
 */
require_once 'ZendX/Service/Wordpress/Abstract.php';

/**
 * Wordpress posts
 * @see ZendX_Service_Wordpress_Blog
 */
require_once 'ZendX/Service/Wordpress/Blog.php';

/**
 * Wordpress posts
 * @see ZendX_Service_Wordpress_Post
 */
require_once 'ZendX/Service/Wordpress/Post.php';

/**
 * Wordpress categories
 * @see ZendX_Service_Wordpress_Category
 */
require_once 'ZendX/Service/Wordpress/Category.php';

/**
 * Wordpress tags
 * @see ZendX_Service_Wordpress_Tag
 */
require_once 'ZendX/Service/Wordpress/Tag.php';

/**
 * Wordpress authors
 * @see ZendX_Service_Wordpress_Author
 */
require_once 'ZendX/Service/Wordpress/Author.php';

class ZendX_Service_Wordpress extends ZendX_Service_Wordpress_Abstract
{
    /**
     * Constructor
     *
     * @param  string  $xmlRpcUrl XML-RPC URL (e.g. blog URL + 'xmlrpc.php')
     * @param  string  $username  Username
     * @param  string  $password  Password
     * @param  string  $blogId    Blog id, only needed for multi-blog
     *                            environments (hosted at wordpress.com or a
     *                            Mu install)
     * @param  boolean $caching   Whether to cache rpc calls for the life of
     *                            the class
     * @return void
     * @throws Zend_Service_Exception if no blog id provided for a
     *                                multi-blog environment
     */
    public function __construct($xmlRpcUrl,
                                $username,
                                $password,
                                $caching = true)
    {
        $this->setXmlRpcUrl($xmlRpcUrl)
             ->setUsername($username)
             ->setPassword($password)
             ->setCaching($caching)
             ->setSkipSystemLookup();
        
        // Setup Zend_XmlRpc_Client
        parent::__construct($xmlRpcUrl);
    }

    /**
     * Retrieves blog information for specified blog
     * @var integer (Defaults to 0) $id 
     * @return ZendX_Service_Wordpress_Abstract
     */
    public function getBlog($id = 0)
    {
        $this->setBlogId($id);
        
        $blog = $this->_getCallObject(
            'wp.getOptions', 'blog', array(
                'blog_id'   =>  $this->getBlogId(),
                'username'  =>  $this->getUsername(),
                'password'  =>  $this->getPassword()
            )
        );
        
        return $blog;
    }

}
