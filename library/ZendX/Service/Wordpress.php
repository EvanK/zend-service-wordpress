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
 * @category   ZendX
 * @package    ZendX_Service
 * @subpackage Wordpress
 */
class ZendX_Service_Wordpress
{
    /**
     * Gettable/settable RPC options
     * @var array
     */
    protected $_options;

    /**
     * Blog name
     * @var string
     */
    protected $_blogName;

    /**
     * Blog url
     * @var string
     */
    protected $_blogUrl;

    /**
     * XML-RPC client
     * @var Zend_XmlRpc_Client
     */
    protected $_client;
    
    /**
     * Cached results of RPC calls
     * @var array
     */
    protected $_cache;
    
    /**
     * Tags fetched via getTags()
     * @var array
     */
    protected $_tags;

    /**
     * Constructor
     *
     * @param  string  $xmlrpc  XML-RPC url, normally the blog url plus 'xmlrpc.php'
     * @param  string  $username  Username
     * @param  string  $password  Password
     * @param  string  $blogid  Blog id, only needed for multi-blog environments (hosted at wordpress.com or a Mu install)
     * @param  boolean $caching Whether to cache rpc calls for the life of the class
     * @return void
     * @throws Zend_Service_Exception if no blog id provided for a multi-blog environment
     */
    public function __construct($xmlrpc, $username, $password, $blogid=0, $caching=TRUE)
    {
        // Store RPC options
        $this->setXmlRpcUrl($xmlrpc)
             ->setUsername($username)
             ->setPassword($password)
             ->setBlogId($blogid)
             ->setCaching($caching);

        /**
         * @see Zend_XmlRpc_Client
         */
        require_once 'Zend/XmlRpc/Client.php';
        $this->_client = new Zend_XmlRpc_Client( $this->getXmlRpcUrl() );

        // Get blog information associated with given user
        $response = $this->_rpc('wp.getUsersBlogs', array( 'username' => $this->getUsername(), 'password' => $this->getPassword() ));

        // Ensure, if a multi-blog system, that we have a valid blog id
        if(count($response) > 1) {
            $response = $this->_getBlogById($response);
            if($response === FALSE) {
                require_once 'Zend/Service/Exception.php';
                throw new Zend_Service_Exception('Multiple blog system on this XML-RPC server, a blog id must be provided');
            }
        }

        // Save id and other blog data
        $this->_options['blogid'] = $response[0]['blogid'];
        $this->_blogName = $response[0]['blogName'];
        $this->_blogUrl = $response[0]['url'];
    }

    /**
     * Retrieves a blog by id from an array of blogs
     * @param $blogs
     * @return mixed
     */
    protected function _getBlogById($blogs) {
        foreach ($blogs as $blog) {
            if(isset($blog['blogid']) && $blog['blogid'] == $this->getBlogId()) {
                return array($blog);
            }
        }

        return FALSE;
    }

    /**
     * Retrieves the result of an xml-rpc call
     * @param string $method
     * @param array $params
     * @return mixed
     */
    protected function _rpc($method, $params) {
        // If caching is turned on
        if($this->getCaching()) {
            // Get cached method results if available
            $signature = serialize(array($method, $params));
            if(isset($this->_cache[$signature])) {
                return $this->_cache[$signature];
            }
            // Otherwise call method and cache results
            else {
                $this->_cache[$signature] = $this->_client->call($method, $params);
                return $this->_cache[$signature];
            }
        }
        // Call method and return results with no caching
        return $this->_client->call($method, $params);
    }

    /**
     * Retrieves whether rpc calls are cached
     * @return boolean
     */
    protected function getCaching() {
        return $this->_options['caching'];
    }

    /**
     * Sets whether rpc calls are cached
     * @param boolean $caching
     * @return ZendX_Service_Wordpress
     */
    protected function setCaching($caching) {
        $this->_options['caching'] = $caching;
        return $this;
    }

    /**
     * Retrieves underlying XML-RPC client
     * @return Zend_XmlRpc_Client
     */
    public function getXmlRpcClient()
    {
        return $this->_client;
    }

    /**
     * Sets underlying XML-RPC client
     * @param $client
     * @return ZendX_Service_Wordpress
     */
    public function setXmlRpcClient($client) {
        if(!($client instanceof Zend_XmlRpc_Client)) {
            require_once 'Zend/Service/Exception.php';
            throw new Zend_Service_Exception('Client provided is not a Zend_XmlRpc_Client');
        }
        
        $this->_client = $client;
        return $this;
    }

    /**
     * Retrieves XML-RPC url
     * @return string
     */
    public function getXmlRpcUrl() {
        return $this->_options['xmlrpc'];
    }

    /**
     * Sets XML-RPC url
     * @param string $url
     * @return ZendX_Service_Wordpress
     * @throws Zend_Service_Exception if non-url provided
     */
    public function setXmlRpcUrl($url) {
        require_once 'Zend/Uri.php';
        if (!Zend_Uri::check($url)) {
            require_once 'Zend/Service/Exception.php';
            throw new Zend_Service_Exception('Invalid url provided for XML-RPC');
        }

        $this->_options['xmlrpc'] = $url;
        return $this;
    }

    /**
     * Retrieves username
     * @return string
     */
    public function getUsername() {
        return $this->_options['username'];
    }

    /**
     * Sets username
     * @param $username
     * @return ZendX_Service_Wordpress
     */
    public function setUsername($username) {
        $this->_options['username'] = $username;
        return $this;
    }

    /**
     * Retrieves password
     * @return string
     */
    public function getPassword() {
        return $this->_options['password'];
    }

    /**
     * Sets password
     * @param $password
     * @return ZendX_Service_Wordpress
     */
    public function setPassword($password) {
        $this->_options['password'] = $password;
        return $this;
    }

    /**
     * Retrieves blog id
     * @return string
     */
    public function getBlogId() {
        return $this->_options['blogid'];
    }

    /**
     * Sets blog id
     * @param $id
     * @return ZendX_Service_Wordpress
     */
    public function setBlogId($id) {
        $this->_options['blogid'] = $id;
        return $this;
    }

    /**
     * Retrieves blog descriptive name
     * @return string
     */
    public function getBlogName() {
        return $this->_blogName;
    }

    /**
     * Retrieves blog url
     * @return string
     */
    public function getBlogUrl() {
        return $this->_blogUrl;
    }

    /**
     * Retrieves N most recent posts
     * @param $count
     * @return array
     */
    public function getRecentPosts($count) {
        $posts = $this->_rpc('metaWeblog.getRecentPosts', array(
            'blogid'        => $this->getBlogId(),
            'username'      => $this->getUsername(),
            'password'      => $this->getPassword(),
            'numberOfPosts' => $count,
        ));
        
        require_once 'ZendX/Service/Wordpress/Post.php';
        foreach($posts as $key => $item) {
            $posts[$key] = new ZendX_Service_Wordpress_Post($this, $item);
        }
        
        return $posts;
    }

    /**
     * Retrieves count of available posts
     * @return int
     */
    public function getPostCount() {
        return count(
            $this->_rpc('mt.getRecentPostTitles', array(
                'blogid'        => $this->getBlogId(),
                'username'      => $this->getUsername(),
                'password'      => $this->getPassword(),
                'numberOfPosts' => 65536,
            ))
        );
    }

    /**
     * Retrieves a post for the given post id
     * @param $id
     * @return ZendX_Service_Wordpress_Post
     * @throws Zend_XmlRpc_Client_FaultException if no post exists for id
     */
    public function getPost($id) {
        require_once 'ZendX/Service/Wordpress/Post.php';
        if($id instanceof ZendX_Service_Wordpress_Post) {
            $id = $id->getId();
        }
        
        return new ZendX_Service_Wordpress_Post(
            $this,
            $this->_rpc('metaWeblog.getPost', array(
                'postid'    => $id,
                'username'  => $this->getUsername(),
                'password'  => $this->getPassword(),
            ))
        );
    }
    
    /**
     * Checks whether a post exists with the given post id
     * @param $id
     * @return boolean
     */
    public function hasPost($id) {
        require_once 'Zend/XmlRpc/Client/FaultException.php';
        try {
            $this->getPost($id);
            return TRUE;
        }
        catch(Zend_XmlRpc_Client_FaultException $e) {
            return FALSE;
        }
    }
    
    /**
     * Retrieves a category by its "categoryId"
     * @returns array Category
     */
    public function getCategory($id) {
        $categories = $this->getCategories();
        
        foreach ($categories as $category) {
            if ($id === $category['categoryId']) {
                return $category;
            }
        }
        require_once 'Zend/Service/Exception.php';
        throw new Zend_Service_Exception(sprintf('Category with id "%s" not found', $id));
    }
    
    /**
     * Retrieves all categories registered with the blog
     * @return array categories
     */
    public function getCategories() {
        return $this->_rpc('wp.getCategories', array(
            'blog_id'   => $this->getBlogId(),
            'username'  => $this->getUsername(),
            'password'  => $this->getPassword()
        ));
    }
    
    /**
     * Return the total number of categories
     * @return int
     */
    public function getCategoryCount()
    {
        return count( $this->getCategories() );
    }
    
    /**
     * Retrieves a tag by its "tag_id"
     * @returns array Tag
     */
    public function getTag($id) {
        $tags = $this->getTags();
        
        foreach ($tags as $tag) {
            if ($id === $tag['tag_id']) {
                return $tag;
            }
        }
        require_once 'Zend/Service/Exception.php';
        throw new Zend_Service_Exception(sprintf('Tag with id "%s" not found', $id));
    }
    
    /**
     * Retrieves all tags registered with the blog
     * @return array Tags
     */
    public function getTags() {
        return $this->_rpc('wp.getTags', array(
            'blog_id'   => $this->getBlogId(),
            'username'  => $this->getUsername(),
            'password'  => $this->getPassword()
        ));
    }
    
    /**
     * Return the total number of tags
     * @return int
     */
    public function getTagCount()
    {
        return count( $this->getTags() );
    }
    
    /*
        @TODO: getTagsAsList() - Create associative array of tags with nesting
                                 based on parentId
    */
    
    /**
     * Retrieves all authors registered with the blog
     * @return array
     */
    public function getAuthors() {
        $authors = $this->_rpc('wp.getAuthors', array(
            'blog_id'        => $this->getBlogId(),
            'username'      => $this->getUsername(),
            'password'      => $this->getPassword(),
        ));
        
        require_once 'ZendX/Service/Wordpress/Author.php';
        foreach($authors as $key => $item) {
            $authors[$key] = new ZendX_Service_Wordpress_Author($this, $item);
        }
        
        return $authors;
    }
    
    /**
     * Retrieves an author for the given author id
     * @param $id
     * @return ZendX_Service_Wordpress_Author
     */
    public function getAuthor($id) {
        require_once 'ZendX/Service/Wordpress/Author.php';
        if($id instanceof ZendX_Service_Wordpress_Author) {
            $id = $id->getId();
        }
        
        $authors = $this->getAuthors();
        foreach($authors as $author) {
            if($author->getId() == $id) {
                return $author;
            }
        }
        require_once 'Zend/Service/Exception.php';
        throw new Zend_Service_Exception('No such author');
    }
    
    /**
     * Retrieves the total number of authors
     * @return int
     */
    public function getAuthorCount() {
        return count($this->getAuthors());
    }
    
    /**
     * Retrieves all pages on the blog
     * @return array
     */
    public function getPages() {
        $pages = $this->_rpc('wp.getPages', array(
            'blog_id'        => $this->getBlogId(),
            'username'      => $this->getUsername(),
            'password'      => $this->getPassword(),
        ));
        
        require_once 'ZendX/Service/Wordpress/Page.php';
        foreach($pages as $key => $item) {
            $pages[$key] = new ZendX_Service_Wordpress_Page($this, $item);
        }
        
        return $pages;
    }
    
    /**
     * Retrieves a page for the given page id
     * @param $id
     * @return ZendX_Service_Wordpress_Page
     */
    public function getPage($id) {
        require_once 'ZendX/Service/Wordpress/Page.php';
        if($id instanceof ZendX_Service_Wordpress_Page) {
            $id = $id->getId();
        }
        
        return new ZendX_Service_Wordpress_Page($this, $this->_rpc('wp.getPage', array(
            'blog_id'       => $this->getBlogId(),
            'page_id'       => $id,
            'username'      => $this->getUsername(),
            'password'      => $this->getPassword(),
        )) );
    }
    
    /**
     * Retrieves the total number of pages on the blog
     * @return int
     */
    public function getPageCount() {
        return count( $this->_rpc('wp.getPageList', array(
            'blog_id'        => $this->getBlogId(),
            'username'      => $this->getUsername(),
            'password'      => $this->getPassword(),
        )) );
    }
    
    /* @TODO:
    get<UNIT>StatusList()
    get<UNIT>Count()
    get<UNIT>(id)
    get<UNIT>s([id])

    Units:
        Post
        Page
        Comment
    */
}
