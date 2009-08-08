<?php
/**
 * ZendX_Service_Wordpress_Author unit tests
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage AuthorTest
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * @see ZendX_Service_Wordpress_Author
 */
require_once 'ZendX/Service/Wordpress/Author.php';

/**
 * @group Wordpress
 * @group Author
 */
class ZendX_Service_Wordpress_AuthorTest extends PHPUnit_Framework_TestCase
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
            $authors = $this->blog->getAuthors();
            $this->author = $authors[0];
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
    
    public function testAuthorHasId()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->author->getId());
    }
    
    public function testAuthorHasLogin()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->author->getLogin());
    }
    
    public function testAuthorHasName()
    {
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
                          $this->author->getName());
    }
    
    public function testAuthorNameIsEquivalentToStringMagicMethod()
    {
        $this->assertEquals($this->author->getName(),
                            (String) $this->author);
    }
}

