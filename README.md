MediaLibraryManagerBundle
=========================

This bundle allow user to have a owner media manager manager.
Actually, there's only music supported. Soon, we will add video, games ... just ask ;)

Installation
------------

This version of the bundle requires Symfony 2.7+.

### Translations

If you wish to use default texts provided in this bundle, you have to make
sure you have translator enabled in your config.

``` yaml
# app/config/config.yml

framework:
    translator: ~
```

For more information about translations, check [Symfony documentation](http://symfony.com/doc/current/book/translation.html).

### Download the bundle with composer

Pretty simple with [Composer](http://packagist.org), run:

```sh
composer require fulgurio/media-library-manager-bundle
```

### Enable the bundle

Enable the bundle in the kernel, with others required bundles:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        new Vich\UploaderBundle\VichUploaderBundle(),
        new Fulgurio\MediaLibraryManagerBundle\FulgurioMediaLibraryManagerBundle(),
    );
}
```

### Import routing file

Now that you have activated and configured the bundle, all that is left to do is
import the routing files.

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
