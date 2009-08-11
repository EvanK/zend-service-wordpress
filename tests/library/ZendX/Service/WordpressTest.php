<?php

/**
 * ZendX_Service_Wordpress unit tests
 *
 * @category   ZendX
 * @package    ZendX_Service
 * @subpackage WordpressTest
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
 * @group Wordpress
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
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testWordpressHasBlog()
    {
        $blog = $this->wordpress->getBlog();
        
        $this->assertEquals('ZendX_Service_Wordpress_Blog', get_class($blog));
    }

}
