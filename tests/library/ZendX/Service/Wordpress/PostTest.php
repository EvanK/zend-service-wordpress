<?php
/**
 * ZendX_Service_Wordpress_Post unit tests
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage PostTest
 * @group      Wordpress
 * @group      Post
 */
/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * @see ZendX_Service_Wordpress_Post
 */
require_once 'ZendX/Service/Wordpress/Post.php';

class ZendX_Service_Wordpress_PostTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        try {
            $this->wordpress = new ZendX_Service_Wordpress(
                TESTS_ZENDX_SERVICE_WORDPRESS_XMLRPC_URL,
                TESTS_ZENDX_SERVICE_WORDPRESS_USERNAME,
                TESTS_ZENDX_SERVICE_WORDPRESS_PASSWORD
            );
            
            $this->blog = $this->wordpress->getBlog();
            $posts = $this->blog->getRecentPosts(1);
            $this->post = $posts[0];
            
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
    
    public function testPostHasId()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->post->getId());
    }
    
    public function testPostHasTitle()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->post->getTitle());
    }
    
    public function testPostHasDescription()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->post->getDescription());
    }
    
    public function testPostDescriptionIsEquivalentToStringMagicMethod()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          (String) $this->post);
        
        $this->assertEquals($this->post->getDescription(),
                            (String) $this->post);
    }
    
    
    public function testPostHasUrl()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->post->getUrl());
    }
    
    public function testPostHasLink()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->post->getLink());
        
        // Ensure link actually starts with a link
        $this->assertEquals(0,
                            strpos($this->post->getLink(), '<a'));
    }
    
    public function testPostHasPermaLink()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->post->getPermaUrl());
        
        // Ensure link actually starts with a link
        $this->assertEquals(0,
                            strpos($this->post->getPermaLink(), '<a'));
    }
    
}
