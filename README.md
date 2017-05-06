# Server

Simple class to emulate Apache's "mod_rewrite" functionality from the built-in PHP web server. This provides a convenient way to test any web application without having installed a "real" web server software here. It works with any cms or framework.

## Installation

This package is installable and autoloadable via Composer as [oscarotero/server](https://packagist.org/packages/oscarotero/server).

```
$ composer require oscarotero/server
```

## Usage

Create a `server.php` file that will be our server script.

```php
//The file exists and can be served as is
if (Server::run()) {
    return false;
}

//Otherwise, go with normal php operations
require __DIR__.'/index.php';
```

Launch the php server:

```sh
php -S localhost:8000 server.php
```

That's all, you could see the web in your browser in `http://localhost:8000`.

### Changing the public directory

By default, [getcwd](http://php.net/getcwd) is used to get the base directory of the server. But it can be changed using the first argument. Example:

```php
//The file exists in public and can be served as is
if (Server::run(__DIR__.'/public')) {
    return false;
}

//Or include the index.php script
require_once __DIR__.'/public/index.php';
```

### Execute php files

If the file has the php extension, the path is returned, so it can be included. This is only needed if the base directory is different that the current directory and the site does not have an unique php file (wordpress sites, for example).

```php
if ($file = Server::run(__DIR__.'/wordpress')) {
    require $file;
}
```

## API

As seen in the previous examples, the method `Server::run()` can return three values:

* `true` Means that the file exists and can be served directly by the php server
* `string` Means that the file exists but must be included (is a php file that cannot be server directly)
* `false` Means that the file does not exists

