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

    public function testXmlRpcClientInstancesAreSeparate()
    {
        $before = new ZendX_Service_Wordpress(
            TESTS_ZENDX_SERVICE_WORDPRESS_XMLRPC_URL,
            'foo',
            'bar'
        );
        
        $after = new ZendX_Service_Wordpress(
            TESTS_ZENDX_SERVICE_WORDPRESS_XMLRPC_URL,
            'baz',
            'bedazzle'
        );
        
        $this->assertNotEquals(
            $before->getXmlRpcClient()->getUsername(),
            $after->getXmlRpcClient()->getUsername()
        );
        
        $this->assertNotEquals(
            $before->getXmlRpcClient()->getPassword(),
            $after->getXmlRpcClient()->getPassword()
        );
        
        $this->assertEquals(
            'foo',
            $before->getXmlRpcClient()->getUsername()
        );
        
        $this->assertEquals(
            'baz',
            $after->getXmlRpcClient()->getUsername()
        );
        
        $this->assertEquals(
            'bar',
            $before->getXmlRpcClient()->getPassword()
        );
        
        $this->assertEquals(
            'bedazzle',
            $after->getXmlRpcClient()->getPassword()
        );
    }
}