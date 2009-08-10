<?php
/**
 * ZendX_Service_Wordpress_Tag unit tests
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage TagTest
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
 * @group Wordpress
 * @group Tag
 */
class ZendX_Service_Wordpress_TagTest extends PHPUnit_Framework_TestCase
{
    public static function tagProvider()
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
        
        return array($wordpress->getBlog()->getTags());
    }
    
    /**
     * @dataProvider tagProvider
     */
    public function testTagHasId($tag)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $tag->getId()
        );
    }
    
    /**
     * @dataProvider tagProvider
     */
    public function testTagHasSlug($tag)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $tag->getSlug()
        );
    }
    
    /**
     * @dataProvider tagProvider
     */
    public function testTagHasName($tag)
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $tag->getName()
        );
    }
    
    /**
     * @dataProvider tagProvider
     */
    public function testAuthorNameIsEquivalentToStringMagicMethod($tag)
    {
        $this->assertEquals(
            $tag->getName(),
            (String) $tag
        );
    }
}

