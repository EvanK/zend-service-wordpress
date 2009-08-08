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
                TESTS_ZENDX_SERVICE_WORDPRESS_PASSWORD
            );
            
            $this->blog = $this->wordpress->getBlog();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
    
    public function testBlogIsZendXServiceWordpressClass()
    {
        $this->assertEquals('ZendX_Service_Wordpress', get_class($this->blog));
    }
    
    public function testBlogHasData()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY,
                          $this->blog->getData());
    }
    
    public function testBlogHasTitle()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->get('blog_title'));
    }
    
    public function testBlogHasTitleMethod()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->getTitle());
    }
    
    public function testBlogHasTitleMagicMethod()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->getBlogTitle());
    }
    
    public function testBlogHasTagline()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->get('blog_tagline'));
    }
    
    public function testBlogHasTaglineMethod()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->getTagline());
    }
    
    public function testBlogHasTaglineMagicMethod()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->getBlogTagline());
    }
    
    public function testBlogHasUrl()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->get('blog_url'));
    }
    
    public function testBlogHasUrlMethod()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->getUrl());
    }
    
    public function testBlogHasUrlMagicMethod()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->getBlogUrl());
    }
    
    public function testBlogHasLink()
    {
        $link = sprintf('<a href="%s" title="%s">%s</a>', $this->blog->getUrl(),
                                                          $this->blog->getTagline(),
                                                          $this->blog->getTitle());
        
        $this->assertEquals($link, $this->blog->getLink());
    }
    
    public function testBlogHasDateFormat()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->getDateFormat());
    }
    
    public function testBlogHasTimeFormat()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->blog->getTimeFormat());
    }
/*
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
                $postid = $posts[0]->getId();
            }
        }
        
        # get specific post if we have a viable id
        if(!is_null($postid)) {
            $this->assertTrue($this->wordpress->hasPost($postid));
            $post = $this->wordpress->getPost($postid);
            $this->assertType('ZendX_Service_Wordpress_Post', $post);
            $this->assertEquals($postid, $post->getId());
        }
    }
    
    public function testPostCategories() {
        $this->markTestIncomplete('Not yet implemented');
        $posts = $this->wordpress->getRecentPosts(1);
        # skip if no posts in blog
        if(count($posts) < 1) {
            $this->markTestSkipped('No posts to test');
        }
        $categories = $post->getCategories();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $categories);
    }
    
    public function testPostBy() {
        $this->markTestIncomplete('Not yet implemented');
        #skip if no posts in blog
        if($this->wordpress->getPostCount() < 1) {
            $this->markTestSkipped('No posts to test');
        }
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $this->wordpress->getPostsByCategory());
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $this->wordpress->getPostsByTag());
    }
    
    public function testCategories() {
        $total = $this->wordpress->getCategoryCount();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_INT, $total);
        $this->assertGreaterThanOrEqual(1, $total);
        
        $categories = $this->wordpress->getCategories();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $categories);
        $this->assertEquals($total, count($categories));
        
        $first_category = $categories[0];
        $category = $this->wordpress->getCategory($first_category['categoryId']);
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $category);
        $this->assertEquals($category, $first_category);
    }
    
    public function testTags() {
        $total = $this->wordpress->getTagCount();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_INT, $total);
        $this->assertGreaterThanOrEqual(1, $total);
        
        $tags = $this->wordpress->getTags();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $tags);
        $this->assertEquals($total, count($tags));
        
        $first_tag = $tags[0];
        $tag = $this->wordpress->getTag($first_tag['tag_id']);
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $tag);
        $this->assertEquals($tag, $first_tag);
    }
    
    public function testAuthors() {
        $authors = $this->wordpress->getAuthors();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $authors);
        $this->assertType('ZendX_Service_Wordpress_Author', $this->wordpress->getAuthor($authors[0]));
        
        $total = $this->wordpress->getAuthorCount();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_INT, $total);
        $this->assertGreaterThanOrEqual(1, $total);
    }
    
    public function testPages() {
        $pages = $this->wordpress->getPages();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $pages);
        $this->assertType('ZendX_Service_Wordpress_Page', $this->wordpress->getPage($pages[0]));
        
        $total = $this->wordpress->getPageCount();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_INT, $total);
        $this->assertGreaterThanOrEqual(1, $total);
    }
/***/
}
