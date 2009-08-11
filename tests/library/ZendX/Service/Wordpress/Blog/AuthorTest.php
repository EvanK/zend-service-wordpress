<?php
/**
 * ZendX_Service_Wordpress_Blog_Author unit tests
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage AuthorTest
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../../../TestHelper.php';

/**
 * @see ZendX_Service_Wordpress
 */
require_once 'ZendX/Service/Wordpress.php';

/**
 * @group Wordpress
 * @group Author
 */
class ZendX_Service_Wordpress_Blog_AuthorTest extends PHPUnit_Framework_TestCase
{
    public static function authorProvider()
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
        
        return array($wordpress->getBlog()->getAuthors());
    }
    
    /**
     * @dataProvider authorProvider
     */
    public function testAuthorHasId($author)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $author->getId()
        );
    }
    
    /**
     * @dataProvider authorProvider
     */
    public function testAuthorHasLogin($author)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $author->getLogin()
        );
    }
    
    /**
     * @dataProvider authorProvider
     */
    public function testAuthorHasName($author)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $author->getName()
        );
    }
    
    /**
     * @dataProvider authorProvider
     */
    public function testAuthorNameIsEquivalentToStringMagicMethod($author)
    {
        $this->assertEquals(
            $author->getName(),
            (String) $author
        );
    }
}

