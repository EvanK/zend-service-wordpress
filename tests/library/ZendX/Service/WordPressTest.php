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
        $this->wordpress = new ZendX_Service_Wordpress(
            TESTS_ZENDX_SERVICE_WORDPRESS_XMLRPC_URL,
            TESTS_ZENDX_SERVICE_WORDPRESS_USERNAME,
            TESTS_ZENDX_SERVICE_WORDPRESS_PASSWORD,
            TESTS_ZENDX_SERVICE_WORDPRESS_BLOGID
        );
    }

    public function testGetRpcClient() {
        $this->assertType('Zend_XmlRpc_Client', $this->wordpress->getXmlRpcClient());
        $this->wordpress->setXmlRpcClient(new Zend_XmlRpc_Client());
    }

    public function testSetRpcClient() {
        $this->assertType(
            'Zend_XmlRpc_Client',
            $this->wordpress->getXmlRpcClient()
        );
    }
}
