MediaLibraryManagerBundle
=========================

This bundle allow user to have a owner media manager manager. 
Actually, there's only music supported. Soon, we will add video, games ... just ask ;)

Installation
------------

This version of the bundle requires Symfony 2.1+.

### Translations

If you wish to use default texts provided in this bundle, you have to make
sure you have translator enabled in your config.

``` yaml
# app/config/config.yml

framework:
    translator: ~
```

For more information about translations, check [Symfony documentation](http://symfony.com/doc/current/book/translation.html).

### Download the bunble with composer

Add MediaLibraryBundle in your composer.json:

```js
{
    "require": {
         "fulgurio/media-library-manager-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update
```

Composer will install the bundle to your project's `vendor/fulgurio` directory.

### Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        new Fulgurio\MediaLibraryManagerBundle\FulgurioMediaLibraryManagerBundle(),
    );
}
```
*note: the bundle use KnpPaginatorBundle, it's automatically imported, you just need to add into AppKerner.php like previously

### Import routing file

Now that you have activated and configured the bundle, all that is left to do is
import the FOSUserBundle routing files.

By importing the routing files you will have ready made pages for things such as
logging in, creating users, etc.

In YAML:
``` yaml
# app/config/routing.yml
fulgurio_media_library_manager:
    resource: "@FulgurioMediaLibraryManagerBundle/Resources/config/routing.yml"
    prefix:   /
```

### Update your database schema

For ORM run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```
