<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage PostTest
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * @see ZendX_Service_Wordpress
 */
require_once 'ZendX/Service/Wordpress.php';

/**
 * @group   Wordpress
 * @group   Post
 */
class ZendX_Service_Wordpress_PostTest extends PHPUnit_Framework_TestCase
{
    public static function postProvider()
    {
        try {
            $wordpress = new ZendX_Service_Wordpress(
                TESTS_ZENDX_SERVICE_WORDPRESS_XMLRPC_URL,
                TESTS_ZENDX_SERVICE_WORDPRESS_USERNAME,
                TESTS_ZENDX_SERVICE_WORDPRESS_PASSWORD
            );
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
        
        return array($wordpress->getBlog()->getRecentPosts(1));
    }
    
    /**
     * @dataProvider postProvider
     */
    public function testPostHasId($post)
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $post->getId());
    }
    
    /**
     * @dataProvider postProvider
     */
    public function testPostHasTitle($post)
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $post->getTitle());
    }
    
    /**
     * @dataProvider postProvider
     */
    public function testPostHasDescription($post)
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $post->getDescription());
    }
    
    /**
     * @dataProvider postProvider
     */
    public function testPostDescriptionIsEquivalentToStringMagicMethod($post)
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          (String) $post);
        
        $this->assertEquals($post->getDescription(),
                            (String) $post);
    }
    
    /**
     * @dataProvider postProvider
     */
    public function testPostHasUrl($post)
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $post->getUrl());
    }
    
    /**
     * @dataProvider postProvider
     */
    public function testPostHasLink($post)
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $post->getLink());
        
        // Ensure link actually starts with a link
        $this->assertEquals(0, strpos($post->getLink(), '<a'));
    }
    
    /**
     * @dataProvider postProvider
     */
    public function testPostHasPermaLink($post)
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $post->getPermaUrl());
        
        // Ensure link actually starts with a link
        $this->assertEquals(0, strpos($post->getPermaLink(), '<a'));
    }
    
    /**
     * @dataProvider postProvider
     */
    public function testPostHasCategories($post)
    {
        $this->markTestIncomplete('Missing post categories test');
    }
}
