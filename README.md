# PostmarkBundle
Symfony2 bundle for [Postmark](http://postmarkapp.com) API [![Build Status](https://secure.travis-ci.org/miguel250/PostmarkBundle.png?branch=master)](http://travis-ci.org/miguel250/PostmarkBundle)
## Setup

**Using Composer**
Add PostmarkBundle in your composer.json:

```js
{
    "require": {
        "mlpz/postmark-bundle": "*"
    }
}
```

``` bash
$ php composer.phar update mlpz/postmark-bundle
```

**Using Submodule**

    git submodule add https://github.com/miguel250/PostmarkBundle.git vendor/bundles/MZ/PostmarkBundle
    git submodule add https://github.com/kriswallsmith/Buzz.git  vendor/buzz

**Add the MZ namespace to autoloader**
You can skip this when using Composer

``` php
<?php
   // app/autoload.php
   $loader->registerNamespaces(array(
    // ...
    'MZ'               => __DIR__.'/../vendor/bundles',
    'Buzz'             => __DIR__.'/../vendor/buzz/lib',
  ));
```
**Add PostmarkBundle to your application kernel**

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new MZ\PostmarkBundle\MZPostmarkBundle(),
    );
}
```

**Enable Postmark in config.yml**
``` yml
mz_postmark:
    api_key: API KEY
    from_email: info@my-app.com
    from_name: My App, Inc
	use_ssl: true
```

## Usage

**Message Service**
``` php
<?php
  $message  = $this->get('postmark.message');
  $message->addTo('test@gmail.com', 'Test Test');
  $message->setSubject('subject');
  $message->setHTMLMessage('<b>email body</b>');
  $message->send()

  $message->addTo('test2@gmail.com', 'Test2 Test');
  $message->setSubject('subject2');
  $message->setHTMLMessage('<b>email body</b>');
  $message->addAttachment(new Symfony\Component\HttpFoundation\File\File(__FILE__));
  $message->send()
```