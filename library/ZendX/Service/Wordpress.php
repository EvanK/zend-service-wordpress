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
     * XML-RPC URL for blog
     * @var _xmlRpcUrl
     */
    protected $_xmlRpcUrl;
    
    /**
     * Blog username
     * @var string _username
     */
    protected $_username;
    
    /**
     * Blog password
     * @var string _password
     */
    protected $_password;
    
    /**
     * Blog ID
     * @var integer _blogId
     */
    protected $_blogId;
    
    /**
     * Whether or not caching is enabled
     * @var boolean _caching
     */
    protected $_caching;
    
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
     * Facilitates redundant XML-RPC calls that get converted into subclasses
     */
    protected function _getCallObjects($method, $objectType, $params = array())
    {
        // Get user's credentials
        $credentials = array(
            'username'  =>  $this->getUsername(),
            'password'  =>  $this->getPassword()
        );
        
        // Add on any additional parameters to credentials
        $params = array_merge($credentials, $params);
        
        // Identify the service (wp, metaWeblog, etc.) and the action
        list($service, $action) = explode(".", $method);
        
        // Each service identifies the "blog id" differently
        switch ($service) {
            case 'metaWeblog':
                $blog = array('blogid' => $this->getBlogId());
                break;
            default:
                $blog = array('blog_id' => $this->getBlogId());
                break;
        }
        
        // Push blog identifier onto beginning of parameters
        $params = array_merge($blog, $params);
        
        // Call requested service
        $results = $this->call("$service.$action", $params);
        
        // Define class name based on object type (Post, Tag, etc.)
        $className = "ZendX_Service_Wordpress_" . ucfirst($objectType);
        
        $objects = array();
        foreach ($results as $data) {
            // New class uses the same XML-RPC URL & HTTP Client as Wordpress
            $object = new $className($this->getXmlRpcUrl(),
                                     $this->getHttpClient());
            $object->setData($data);
            
            array_push($objects, $object);
        }
        
        return $objects;
    }
    
    /**
     * Retrieves blog information for specified blog
     * @var integer (Defaults to 0) $id 
     * @return ZendX_Service_Wordpress_Abstract
     */
    public function getBlog($id = 0)
    {
        $this->setBlogId($id);
        
        $options = $this->call(
            'wp.getOptions', array(
                'blog_id'   =>  $this->getBlogId(),
                'username'  =>  $this->getUsername(),
                'password'  =>  $this->getPassword()
            )
        );
        
        $data = array();
        
        foreach ($options as $key => $option) {
            $data[$key] = $option['value'];
        }
        
        $this->setData($data);
        
        return $this;
    }
    
    /**
     * @return Blog ID
     */
    public function getBlogId()
    {
        return $this->_blogId;
    }
    
    /**
     * @return Username
     */
    public function getUsername()
    {
        return $this->_username;
    }
    
    /**
     * @return Password
     */
    public function getPassword()
    {
        return $this->_password;
    }
    
    public function getXmlRpcUrl()
    {
        return $this->_xmlRpcUrl;
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
    
    /**
     * Retrieve Wordpress username
     * @return string username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
        
        return $this;
    }
    
    /**
     * Retrieve Wordpress password
     * @return string password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
        
        return $this;
    }
    
    /**
     * Retrieve current blog ID
     * @return integer blog ID
     */
    public function setBlogId($id)
    {
        $this->_blogId = $id;
    }
    
    /**
     * Enable/Disable caching
     * @return ZendX_Service_Wordpress
     */
    public function setCaching($caching)
    {
        $this->_caching = $caching;
        
        return $this;
    }
    
    /**
     * Set XML-RPC URL
     * @return ZendX_Service_Wordpress
     */
    public function setXmlRpcUrl($xmlRpcUrl)
    {
        $this->_xmlRpcUrl = $xmlRpcUrl;
        
        return $this;
    }
}
