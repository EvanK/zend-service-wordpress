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
     * @param  string  $xmlRpcUrl XML-RPC URL, normally the blog URL plus 'xmlrpc.php'
     * @param  string  $username  Username
     * @param  string  $password  Password
     * @param  string  $blogId    Blog id, only needed for multi-blog environments (hosted at wordpress.com or a Mu install)
     * @param  boolean $caching   Whether to cache rpc calls for the life of the class
     * @return void
     * @throws Zend_Service_Exception if no blog id provided for a multi-blog environment
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
        
        $options = $this->call('wp.getOptions', array(
            'blog_id'   =>  $this->getBlogId(),
            'username'  =>  $this->getUsername(),
            'password'  =>  $this->getPassword()
        ));
        
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
     *@var integer (Defaults to 10) limit
     */
    public function getRecentPosts($limit = 10)
    {
        $results = $this->call('metaWeblog.getRecentPosts', array(
            'blogid'    =>  $this->getBlogId(),
            'username'  =>  $this->getUsername(),
            'password'  =>  $this->getPassword()
        ));
        
        $posts = array();
        foreach ($results as $data) {
            $post = new ZendX_Service_Wordpress_Post($this->getXmlRpcUrl(),
                                                     $this->getHttpClient());
            $post->setData($data);
            
            array_push($posts, $post);
        }
        
        return $posts;
    }
    
    public function setUsername($username)
    {
        $this->_username = $username;
        
        return $this;
    }
    
    public function setPassword($password)
    {
        $this->_password = $password;
        
        return $this;
    }
    
    public function setBlogId($id)
    {
        $this->_blogId = $id;
    }
    
    public function setCaching($caching)
    {
        $this->_caching = $caching;
        
        return $this;
    }
    
    public function setXmlRpcUrl($xmlRpcUrl)
    {
        $this->_xmlRpcUrl = $xmlRpcUrl;
        
        return $this;
    }
}
