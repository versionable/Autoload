README
======

What is Autoload?
-----------------

Autoload is a php5.3 autoloader based on the SplClassLoader implementation that
implements the technical interoperability standards for PHP 5.3 namespaces and
class names.

Requirements
------------

PHP 5.3.2 and up.

Usage
-----

<?php

  // Example which loads classes for the Doctrine Common package and Symfony
  // in the Doctrine\Common namespace and Symfony namespace.
  include 'src/Versionable/Autoload/Autoload.php';

  use Versionable\Autoload\Autoload;

  $classLoader = new Autoload();
  $classLoader->registerNamespace('Doctrine\Common', '/path/to/doctrine');
  $classLoader->registerNamespace('Symfony', '/path/to/symfony');
  $classLoader->register();

?>