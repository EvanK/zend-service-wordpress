<?php

/**
 * ZendX_Service_Wordpress_XmlRpc_Client unit tests
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress_XmlRpc
 * @subpackage Client
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
 * @group XmlRpc
*/
class ZendX_Service_Wordpress_XmlRpc_ClientTest extends PHPUnit_Framework_TestCase
{
    
    public function blogProvider()
    {
        $wordpress = new ZendX_Service_Wordpress(
            TESTS_ZENDX_SERVICE_WORDPRESS_XMLRPC_URL,
            TESTS_ZENDX_SERVICE_WORDPRESS_USERNAME,
            TESTS_ZENDX_SERVICE_WORDPRESS_PASSWORD
        );
        
        return array(
            array($wordpress->getBlog())
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testXmlRpcClientCacheIsEnabledByDefault($blog)
    {
        $this->assertEquals(
            true,
            (bool) $blog->getXmlRpcClient()->getCaching()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testXmlRpcClientCacheIsDisabled($blog)
    {
        $blog->getXmlRpcClient()->setCaching(false);
        $this->assertEquals(
            false,
            (bool) $blog->getXmlRpcClient()->getCaching()
        );
    }

    /**
     * @dataProvider blogProvider
     */
    public function testXlmRpcClientCacheIsToggleable($blog)
    {
        $blog->getXmlRpcClient()->setCaching(false);
        $blog->getXmlRpcClient()->setCaching(true);
        $this->assertEquals(
            true,
            (bool) $blog->getXmlRpcClient()->getCaching()
        );
        
        $blog->getXmlRpcClient()->setCaching(false);
        $this->assertEquals(
            false,
            (bool) $blog->getXmlRpcClient()->getCaching()
        );
    }
    
}