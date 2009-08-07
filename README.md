[wordpress]: http://wordpress.org/ "WordPress"
[twitter]: http://framework.zend.com/manual/en/zend.service.twitter.html "Zend_Service_Twitter"
[xmlrpc]: http://codex.wordpress.org/XML-RPC_wp "WordPress XML-RPC"
[phpunit]: http://www.phpunit.de/manual/current/en/installation.html "PHPUnit Installation"

# ZendX\_Service\_Wordpress

Similar to [Zend\_Service\_Twitter][twitter], this service allows you to easily consume
a [WordPress][wordpress] blog's content via [WordPress' XML-RPC][xmlrpc] gateway.

## Usage

Zend\_Service\_Wordpress tries to keep a small, consistent API that returns results
based on the context of the call.

For example, you can call `getCategories()` on
both the `ZendX_Service_Wordpress` and `ZendX_Service_Post`.

With the first,
you'll receive all of the categories for the entire blog while the latter returns
only the categories attached to that post (as `ZendX_Service_Wordpress_Category`
objects, of course).

### Connect to your blog:
    
    // ZendX_Service_Wordpress(XMLRPC_URL, USERNAME, PASSWORD[, BLOG_ID])
    $blog = new ZendX_Service_Wordpress('http://wordpress-site.com/xmlrpc.php', 'username', 'password', 0);
    
### Get various site information:
    
    echo $blog->getTitle();         // My WordPress Blog
    echo $blog->getDescription();   // Another WordPress Blog
    echo $blog->getUrl();           // http://wordpress-site.com/
    echo $blog->getLink();          // <a href="http://wordpress-site.com" title="My WordPress Blog">My WordPress Blog</a>
    // etc...
    
### List all tags:
    
    foreach ($blog->getTags() as $tag) {
        // Uses ZendX_Service_Wordpress_Tag object
        echo sprintf('<a href="%s">%s</a>', $tag->getUrl(), $tag);
        // or
        echo $tag->getLink();
    }
    
### List all categories:
    
    foreach ($blog->getCategories() as $category) {
        // Uses ZendX_Service_Wordpress_Category object
        echo sprintf('<a href="%s">%s</a>', $category->getUrl(), $category);
        // or
        echo $category->getLink();
    }

### Get recent posts
    
    foreach ($blog->getRecentPosts(10) as $post) {
        // Uses ZendX_Service_Wordpress_Post object
        echo $post->getLink();
    }

### Get post information

    echo $post->getTitle();
    echo $post->getExcerpt();
    
    echo $post->getBody();
    // or
    echo $post  // __toString method calls `getBody()`
    
    foreach ($post->getTags() as $tag) {
        echo $tag->getLink();
    }
    
    foreach ($post->getCategories() as $category) {
        echo $category->getLink();
    }

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

If you want to run a specific test, simply run `phpunit --filter testTags`.
