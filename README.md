# Usher
PSR-7 Router Based on FastRoute

Once configured with any number of named routes the router will accept a PSR-7 Compatible request object 
and return a immutable copy of it annotated with attributes for route derived values from URL placeholder 
or defined at the time the route was defined.

In addition a special attribute is set on the Request object called matching_route that contains the
route object that was matched.  

There is also an API for creating new URI's given a route definition from a route object.

```php
 $route = HttpRoute::get('route-name', '/users/{user_id}[/{name}]');
 echo $route->buildUrl(['user_id' => 100);
 // /users/100
 echo $route->buildUrl(['user_id' => 100, 'name'=> 'jcleese']);
 // /users/100/jcleese
```
General use for the router is as follows.

```php
use Usher\Router;
use Usher\HttpRoute;

$router = new Router('', [
  HttpRoute::get(
    'route-name', 
    '/articles/{id:\d+}[/{name}]', 
    'dispatch-target', 
    ['some-parameter'=>'default']
  )
]);

$request = new Zend\Diactoros\ServerRequest();
$uri = new Zend\Diactoros\Uri('http://localhost/articles/100/my-article');
$request = $request->withUri($uri);
$request = $router->route($request);

echo $request->getAttribute('id');
// 100
echo $request->getAttribute('name');
// my-article

echo $request->getAttribute('some-parameter');
// default

echo $request->getAttribute('matched_route')->buildUrl(['id'=>101, 'name'=>'my-new-article']);
// /articles/101/my-new-article
```
