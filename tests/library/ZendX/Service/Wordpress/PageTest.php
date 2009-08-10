<?php
/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage UnitTests
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../../TestHelper.php';

/**
 * @see ZendX_Service_Wordpress_Page
 */
require_once 'ZendX/Service/Wordpress/Page.php';

/**
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage UnitTests
 */
class ZendX_Service_Wordpress_PageTest extends PHPUnit_Framework_TestCase
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
            
            $pages = $this->wordpress->getPages();
            $this->page = $pages[0];
        }
        catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testNormalizedKeys()
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $this->page->getDateCreated()
        );
        
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $this->page->getWpAuthorDisplayName()
        );

        $this->setExpectedException('Zend_Service_Exception');
        $this->page->getNoPossibleWayThisMethodWillExist();
        $this->fail('Did not throw expected service exception');
    }
    
    public function testDefinedGetters()
    {
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_NUMERIC,
            $this->page->getId()
        );
        
        $this->assertType(
            PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
            $this->page->getSlug()
        );
    }
}
