ZfSnapPhpDebugBar
=================

PHP Debug Bar module for Zend Framework 2

Created by Witold Wasiczko

![PHP Debug Bar Messages](http://www.psd2html.pl/public/ZfSnapPhpDebugBar/ZfSnapPhpDebugBar1.png)

How to install?
---------------
By [composer.json](https://getcomposer.org/)
```json
{
    "require": {
        "snapshotpl/zf-snap-php-debug-bar": "dev-master"
    }
}
```

run `composer update` and add module `ZfSnapPhpDebugBar` to ZF2 `application.config.php`. That's all!

Features
--------

* All [PHP Debug Bar](http://phpdebugbar.com/) features + additional info about ZF2 application:
  * Log and debug directly to Debug Bar,
  * Check request variables ($_POST, $_GET, $_SERVER, $_COOKIE),
  * Use timeline to see time between common events,
  * Catch exceptions,
  * Preview config and application config,
  * Profile SQL queries,
  * Details about current route,
  * Memory usage,
  * Total request duration,
  * Custom debug bar view,
  * and [more](http://phpdebugbar.com/)...!
* Ready to use - just install via composer.json!
* easy configurable via module config,

How to use `Message` tab?
-----------------------------

By function:

```php
debugbar_log('ZfSnapPhpDebugBar is awesome!');
```

By static method:

```php
\ZfSnapPhpDebugBar\Module::log('ZfSnapPhpDebugBar is awesome!');
```

By `Zend\Log`:

```php
$writer = $sm->get('ZfSnapPhpDebugBar\Log\Writer\PhpDebugBar');
$log = new \Zend\Log\Logger();
$log->addWriter($writer);
$log->info('ZfSnapPhpDebugBar is awesome!');
```

Directly by DebugBar object from ServiceManager:
```php
$debugbar = $sm->get('debugbar');
$debugbar['messages']->addMessage('ZfSnapPhpDebugBar is awesome!'));
```

How to config?
--------------
Look at `config/zfsnapphpdebugbar.config.php`

![PHP Debug Bar Timeline](http://www.psd2html.pl/public/ZfSnapPhpDebugBar/ZfSnapPhpDebugBar2.png)

![PHP Debug Bar Request](http://www.psd2html.pl/public/ZfSnapPhpDebugBar/ZfSnapPhpDebugBar3.png)

![PHP Debug Bar Config](http://www.psd2html.pl/public/ZfSnapPhpDebugBar/ZfSnapPhpDebugBar4.png)

![PHP Debug Bar Route](http://www.psd2html.pl/public/ZfSnapPhpDebugBar/ZfSnapPhpDebugBar5.png)
