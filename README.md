# Usher
PSR-7 Router Based on FastRoute

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
