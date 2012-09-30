ZakharovviHumansTxtBundle
==================================

Generate humans.txt from git repository.

About humans.txt you may learn here: http://humanstxt.org/

Requirements
------------

Installed git.

Initialized git repository in your Symfony 2 project

Installation
------------

1.  Add this to the `composer.json`:

``` json
{
    "require": {
        "zakharovvi/humanstxt-bundle": "dev-master"
    }
}
```

    And run:

``` bash
$ php composer.phar update zakharovvi/humanstxt-bundle
```

2.  Enable the bundle in `app/AppKernel.php`:

``` php
public function registerBundles()
{
    $bundles = array(
        // ...
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            // ...
            $bundles[] = new Zakharovvi\HumansTxtBundle\ZakharovviHumansTxtBundle();
        }
    );
}
```

Usage
-----

``` bash
$ cd /path/to/your/project
$ php app/console humans_txt:generate
```

Resources
---------

You can run the unit tests with the following command:

``` bash
$ phpunit
```