<?php

/**
 * ZendX_Service_Wordpress_Blog unit tests
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage BlogTest
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
 * @group Wordpress
 * @group Blog
*/
class ZendX_Service_Wordpress_BlogTest extends PHPUnit_Framework_TestCase
{

    public static function blogProvider()
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
        
        return array(
            array($wordpress->getBlog())
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogIsZendXServiceWordpressClass($blog)
    {
        $this->assertEquals('ZendX_Service_Wordpress_Blog', get_class($blog));
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasData($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY,
            $blog->getData()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasTitle($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->get('blog_title')
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasTitleMethod($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->getTitle()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasTitleMagicMethod($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->getBlogTitle()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasTagline($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->get('blog_tagline')
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasTaglineMethod($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->getTagline()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasTaglineMagicMethod($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->getBlogTagline()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasUrl($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->get('blog_url')
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasUrlMethod($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->getUrl()
        );
    }
    
    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasUrlMagicMethod($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->getBlogUrl()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasLink($blog)
    {
        $this->assertEquals(0, strpos($blog->getLink(), '<a'));
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasDateFormat($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->getDateFormat()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasTimeFormat($blog)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $blog->getTimeFormat()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasRecentPosts($blog)
    {
        $posts = $blog->getRecentPosts();
        
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY,
            $posts
        );
        
        $this->assertGreaterThan(1, count($posts));
        $this->assertLessThanOrEqual(10, count($posts));
        
        // To ease debugging, we'll do a string comparison of what the
        // class name *should be*
        $classControl = array();
        $classTest    = array();
        foreach ($posts as $post) {
            array_push($classControl, 'ZendX_Service_Wordpress_Blog_Post');
            array_push($classTest, get_class($post));
        }
        
        $this->assertEquals(
            join(', ', $classControl),
            join(', ', $classTest)
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasAuthors($blog)
    {
        $authors = $blog->getAuthors();
        
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY,
            $authors
        );
        
        $this->assertGreaterThanOrEqual(1, count($authors));
        
        // To ease debugging, we'll do a string comparison of what the
        // class name *should be*
        $classControl = array();
        $classTest    = array();
        foreach ($authors as $author) {
            array_push($classControl, 'ZendX_Service_Wordpress_Blog_Author');
            array_push($classTest, get_class($author));
        }
        $this->assertEquals(
            join(', ', $classControl),
            join(', ', $classTest)
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasCategories($blog)
    {
        $categories = $blog->getCategories();
        
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY,
            $categories
        );
        
        $this->assertGreaterThanOrEqual(1, count($categories));
        
        $classControl = array();
        $classTest    = array();
        foreach ($categories as $category) {
            array_push($classControl, 'ZendX_Service_Wordpress_Blog_Category');
            array_push($classTest, get_class($category));
        }
        
        $this->assertEquals(
            join(', ', $classControl),
            join(', ', $classTest)
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testBlogHasTags($blog)
    {
        $tags = $blog->getTags();
        
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY,
            $tags
        );
        
        $this->assertGreaterThanOrEqual(1, count($tags));
        
        $classControl = array();
        $classTest    = array();
        foreach ($tags as $tag) {
            array_push($classControl, 'ZendX_Service_Wordpress_Blog_Tag');
            array_push($classTest, get_class($tag));
        }
        
        $this->assertEquals(
            join(', ', $classControl),
            join(', ', $classTest)
        );
    }
}
