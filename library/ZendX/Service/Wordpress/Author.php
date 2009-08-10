<?php

/**
 * Zend Framework
 *
 * @category   ZendX
 * @package    ZendX_Service_Wordpress
 * @subpackage Author
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see ZendX_Service_Wordpress_Abstract
 */
require_once 'ZendX/Service/Wordpress/Abstract.php';

class ZendX_Service_Wordpress_Author extends ZendX_Service_Wordpress_Abstract
{

    /**
     * Retrieves author id
     * @return string
     */
    public function getId()
    {
        return $this->get('user_id');
    }

    /**
     * Retrieves author display name
     * @return string
     */
    public function getName()
    {
        return $this->get('display_name');
    }

    /**
     * Retrieves author login name
     * @return string
     */
    public function getLogin()
    {
        return $this->get('user_login');
    }

    /**
     * Returns author name when cast as string
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

}
