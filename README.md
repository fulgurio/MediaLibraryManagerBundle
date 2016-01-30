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

### Download the bundle with composer

Pretty simple with [Composer](http://packagist.org), run:

```sh
composer require fulgurio/media-library-manager-bundle:dev-master
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
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Fulgurio\MediaLibraryManagerBundle\FulgurioMediaLibraryManagerBundle(),
    );
}

### Set the configuration of bundles

``` yaml
# app/config/config.yml
vich_uploader:
    db_driver: orm # or mongodb or propel or phpcr
    mappings:
        music_cover:
            uri_prefix:         /images/music
            upload_destination: %kernel.root_dir%/uploads/music

# Gedmo bundle
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true

# Liip imagine bundle
liip_imagine:
    resolvers:
        default:
            web_path:
                cache_prefix: cache
    filter_sets:
        book_cover_thumb:
            quality:        75
            filters:
                thumbnail:  { size: [60, 75], mode: outbound }
        music_cover_thumb:
            quality: 75
            filters:
                thumbnail:  { size: [75, 75], mode: outbound }
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
