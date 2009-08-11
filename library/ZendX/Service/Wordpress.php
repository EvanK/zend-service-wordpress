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
        $client = $this->getXmlRpcClient($xmlRpcUrl);
        $client->setUsername($username)
               ->setPassword($password)
               ->setCaching($caching)
               ->setSkipSystemLookup();
    }

    /**
     * Retrieves blog information for specified blog
     * @param  string  id   Blog id, only needed for multi-blog
     *                      environments (hosted at wordpress.com or a Mu install)
     * @return ZendX_Service_Wordpress_Abstract
     */
    public function getBlog($id = 0)
    {
        $client = $this->getXmlRpcClient()->setBlogId($id);
        $data = $client->callWithCredentials('wp.getOptions');
        
        foreach ($data as $key => $option) {
            $data[$key] = $option['value'];
        }
        
        $blog = new ZendX_Service_Wordpress_Blog($client);
        $blog->setData($data);
        
        return $blog;
    }

}
