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
    translator:      { fallbacks: ["%locale%"] }
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
```
### Set the configuration of bundles

``` yaml
# app/config/config.yml
twig:
[...]
    form_themes:
        - 'VichUploaderBundle:Form:fields.html.twig'
        - 'FulgurioMediaLibraryManagerBundle:Form:fields.html.twig'
[...]
vich_uploader:
    db_driver: orm # or mongodb or propel or phpcr
    mappings:
        book_cover:
            uri_prefix:         /uploads/book
            upload_destination: %kernel.root_dir%/../web/uploads/book
        music_cover:
            uri_prefix:         /uploads/music
            upload_destination: %kernel.root_dir%/../web/uploads/music

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


### Install assets
There's the [bower](https://bower.io/) config file to install all assets. You need to have bower 
installed on your system, or there's a shell script which uses [docker](https://www.docker.com/).
