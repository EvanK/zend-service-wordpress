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
 * Wordpress Abstract
 * @see ZendX_Service_Wordpress_Abstract
 */
require_once 'ZendX/Service/Wordpress/Abstract.php';

/**
 * Wordpress posts
 * @see ZendX_Service_Wordpress_Blog
 */
require_once 'ZendX/Service/Wordpress/Blog.php';

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
             ->setPassword($password);
        
        $this->getXmlRpcClient()->setCaching($caching)
                                ->setSkipSystemLookup();
    }

    /**
     * Retrieves blog information for specified blog
     * @var integer (Defaults to 0) $id 
     * @return ZendX_Service_Wordpress_Abstract
     */
    public function getBlog($id = 0)
    {
        $this->setBlogId($id);
        
        $data = $this->getXmlRpcClient()->call(
            'wp.getOptions', array(
                'blog_id'   =>  $this->getBlogId(),
                'username'  =>  $this->getUsername(),
                'password'  =>  $this->getPassword()
            )
        );
        
        foreach ($data as $key => $option) {
            $data[$key] = $option['value'];
        }
        
        $blog = new ZendX_Service_Wordpress_Blog();
        
        $blog->setData($data);
        
        return $blog;
    }

}
