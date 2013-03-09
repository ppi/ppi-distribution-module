PPIDistributionModule
===========================

[@php]: http://php.net/ "PHP: Hypertext Preprocessor"
[@ppi]: http://ppi.io/  "The PPI Framework - A meta-framework built using Symfony2/ZendFramework2 and Doctrine2"

The base module for [PPI][@ppi] distributions - *PPI unofficial release*.

[![Build Status](https://secure.travis-ci.org/ppi/ppi-distribution-module.png)](http://travis-ci.org/ppi/ppi-distribution-module)

Requirements
------------

* [PHP][@php] 5.3.3 and up
* [PPI Framework 2][@ppi] (2.1.x)

Installation (Composer)
-----------------------

### 0. Install Composer

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

``` bash
curl -s http://getcomposer.org/installer | php
```

### 1. Add this package to your composer.json

```js
{
    "require": {
        "ppi/ppi-distribution-module": "dev-master"
    }
}
```

Now tell composer to download the module by running the command:

``` bash
$ php composer.phar update ppi/distribution-module
```

Composer will install the module to your project's `vendor/ppi` directory.

### 2. Enable the module

Enable this module by editing `app/config/modules.php`:

``` php
<?php
return array(
    'modules' => array(
        // ...
        'PPIDistributionModule',
    ),
    // ...
);
```

License
-------

This bundle is licensed under the MIT License. See the [LICENSE file](https://github.com/ppi/ppi-distribution-module/blob/master/LICENSE) for details.

Authors
-------

Vítor Brandão - <vitor@noiselabs.org> ~ [twitter.com/noiselabs](http://twitter.com/noiselabs) ~ [noiselabs.org](http://noiselabs.org)

See also the list of [contributors](https://github.com/ppi/ppi-distribution-module/contributors) who participated in this project.

Submitting bugs and feature requests
------------------------------------

Bugs and feature requests are tracked on [GitHub](https://github.com/ppi/ppi-distribution-module/issues).
