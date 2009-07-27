[wordpress]: http://wordpress.org/ "WordPress"
[twitter]: http://framework.zend.com/manual/en/zend.service.twitter.html "Zend_Service_Twitter"
[xmlrpc]: http://codex.wordpress.org/XML-RPC_wp "WordPress' XML-RPC"
[phpunit]: http://www.phpunit.de/manual/current/en/installation.html "PHPUnit Installation"

# Zend\_Service\_Wordpress

Similar to [Zend\_Service\_Twitter][twitter], this service allows you to easily consume
a [WordPress][wordpress] blog's content via [WordPress' XML-RPC][xmlrpc] gateway.

## Usage

More to come, but be sure to use `new ZendX_Service_Wordpress`, since this code is not
official!

## Unit Testing

You can perform unit testing by installing [PHPUnit][phpunit]
(I recommend the **manual** install) and doing...

    # cd zend-service-wordpress/tests
    # phpunit

Hopefully, you should see something like:

> phpunit
> PHPUnit 3.3.9 by Sebastian Bergmann.
> 
> .......
> 
> Time: 18 seconds
> 
> OK (7 tests, 28 assertions)

    