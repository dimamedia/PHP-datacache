# php datacache
Simple file based cache for php variables

**Before:**
```php
$variableA = someSlowFunction(param1, param2);

$variableB = $sql->query("VERY SLOW REQUEST OR HUGE DATA");
```

**After:**
```php
$datacache = new datacache('cache/', 3600); // store files in './cache/' directory for 3600s = 1 hour

if(!$variableA = $dataCache->cache('variableA')) {
  $variableA = someSlowFunction(param1, param2);
  $dataCache->cache('variableA', $variableA);
}

if(!$variableB = $dataCache->cache('variableB')) {
  $variableB = $sql->query("VERY SLOW REQUEST OR HUGE DATA");
  $dataCache->cache('variableB', $variableB);
}
```

## How to use?

```php
require("class.datacache.php");
$datacache = new datacache('cache/', 600);
```

First required parameter is a directory to store files. Should be with trailing slash, eg. *'cache/'*.
Second optional parameter is TTL time in seconds. Leaving it empty will set TTL to default 3600 sec.

```php
// Store cache
$datacache->cache('variable_name', $value);

// Get cache
$variable = $datacache->cache('variable_name');
```

As a *variable_name* you can use the actual variable name or to keep different cache for each page you can add an alias of the page.

Second parameter is a value to cache. So if it's not empty, function will store. By leaving secong parameter empty, function will try to get cache if it available.


Example:

The e-commerce product page: *http://e-commerce.com/product/very-good-phone*

It has an alias `$alias = 'very-good-phone';` that you can use to identify cache for specific page:
```php
if(!$productData = $dataCache->cache($alias.'productData')) {
  $productData = getProductData('very-good-phone');
  $dataCache->cache($alias.'productData', $productData);
}
```

You can also override default TTL for specific purpose by adding own TTL as a third parameter. Leaving second empty.
```php
$productData = $dataCache->cache($alias.'productData', null, 60);
```

## Why?

There is **APC** and **OPcache**. But they are all using shared memory. There is cases that it's better to leave shared memory alone and use storage disk instead for caching. Sure there is *SQL* cache, but it only for requests. On e-commerce site product page has product info, products spare parts, similar product lists, other products purchased with it, etc. These are dynamic contents, so they are usually generated everytime again and again. Template systems like Smarty cache only structure, but not the content.
My expereience is in two e-commerce site, each has over 6000 products. Server renders source code of a product page in about 1.5 sec. But by caching complicated elements of the page, render time is under 0.1 sec.
