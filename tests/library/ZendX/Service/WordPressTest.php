<?php
/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service
 * @subpackage UnitTests
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * @see ZendX_Service_Wordpress
 */
require_once 'ZendX/Service/Wordpress.php';

/**
 * @category   ZendX
 * @package    ZendX_Service
 * @subpackage UnitTests
 */
class ZendX_Service_WordpressTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        try {
            $this->wordpress = new ZendX_Service_Wordpress(
                TESTS_ZENDX_SERVICE_WORDPRESS_XMLRPC_URL,
                TESTS_ZENDX_SERVICE_WORDPRESS_USERNAME,
                TESTS_ZENDX_SERVICE_WORDPRESS_PASSWORD,
                TESTS_ZENDX_SERVICE_WORDPRESS_BLOGID
            );
        }
        catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testRpcClient() {
        $this->assertType('Zend_XmlRpc_Client', $this->wordpress->getXmlRpcClient());
        $this->wordpress->setXmlRpcClient(new Zend_XmlRpc_Client(''));
        $this->assertType('Zend_XmlRpc_Client', $this->wordpress->getXmlRpcClient());

        $this->setExpectedException('Zend_Service_Exception');
        $this->wordpress->setXmlRpcClient(FALSE);
        $this->fail('Last setXmlRpcClient call should have raised an exception');
    }

    public function testRpcUrl() {
        $this->assertEquals(TESTS_ZENDX_SERVICE_WORDPRESS_XMLRPC_URL, $this->wordpress->getXmlRpcUrl());
        $this->wordpress->setXmlRpcUrl('http://example.com/foobar');
        $this->assertEquals('http://example.com/foobar', $this->wordpress->getXmlRpcUrl());

        $this->setExpectedException('Zend_Service_Exception');
        $this->wordpress->setXmlRpcUrl('This is not a URL');
        $this->fail('Last setXmlRpcUrl call should have raised an exception');
    }

    public function testUsername() {
        $this->assertEquals(TESTS_ZENDX_SERVICE_WORDPRESS_USERNAME, $this->wordpress->getUsername());
        $this->wordpress->setUsername('foo');
        $this->assertEquals('foo', $this->wordpress->getUsername());
    }

    public function testPassword() {
        $this->assertEquals(TESTS_ZENDX_SERVICE_WORDPRESS_PASSWORD, $this->wordpress->getPassword());
        $this->wordpress->setPassword('bar');
        $this->assertEquals('bar', $this->wordpress->getPassword());
    }

    public function testBlogId() {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_NUMERIC, $this->wordpress->getBlogId());
        $this->wordpress->setBlogId(99);
        $this->assertEquals(99, $this->wordpress->getBlogId());
    }
    
    public function testBlogData() {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $this->wordpress->getBlogName());
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $this->wordpress->getBlogUrl());
    }

    public function testPosts() {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_INT, $this->wordpress->getPostCount());
        
        # test for up to 5 recent posts
        $postid = NULL;
        foreach (range(1,5) as $n) {
            $posts = $this->wordpress->getRecentPosts($n);
            $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $posts);
            $this->assertLessThanOrEqual($n, count($posts));
            
            # get first available postid
            if(is_null($postid) && count($posts) > 0) {
                $postid = $posts[0]['postid'];
            }
        }
        
        # get specific post if we have a viable id
        if(!is_null($postid)) {
            $this->assertTrue($this->wordpress->hasPost($postid));
            $post = $this->wordpress->getPost($postid);
            $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $post);
            $this->assertEquals($postid, $post['postid']);
        }
    }
/***/
}
