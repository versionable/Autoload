<?php

namespace Autoload;

require __DIR__.'/Exception/AutoloadException.php';
require __DIR__.'/Exception/ClassException.php';
require __DIR__.'/Exception/FileException.php';

use Autoload\Exception\ClassException;
use Autoload\Exception\FileException;

/**
 * Autoloader based on the SplClassLoader implementation that implements the
 * technical interoperability standards for PHP 5.3 namespaces and class names.
 *
 * <code>
 * <?php
 *
 * // Example which loads classes for the Doctrine Common package and Symfony in the
 * // Doctrine\Common namespace and Symfony namespace.
 * $classLoader = new Autoload();
 * $classLoader->registerNamespace('Doctrine\Common', '/path/to/doctrine');
 * $classLoader->registerNamespace('Symfony', '/path/to/symfony');
 * $classLoader->register();
 *
 * ?>
 * </code>
 *
 * <code>
 * <?php
 * 
 * // Example which loads classes for the Doctrine Common package and Symfony in the
 * // Doctrine\Common namespace and Symfony namespace. (Uses new method to group registrations)
 * $classLoader = new Autoload();
 * $classLoader->registerNamespaces(array(
 *   'Doctrine\Common' => '/path/to/doctrine',
 *   'Symfony' => '/path/to/symfony',
 * ));
 * $classLoader->register();
 *
 * ?>
 * </code>
 *
 * @author Harry Walter <harry@versionable.co.uk>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Roman S. Borschel <roman@code-factory.org>
 * @author Matthew Weier O'Phinney <matthew@zend.com>
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 *
 * @package Autoload
 * @filesource
 */
class Autoload
{
  /**
   *
   * @var string
   */
  private $namespaceSeparator = '\\';
  /**
   *
   * @var string
   */
  private $fileExtension = '.php';
  /**
   *
   * @var array
   */
  private $namespaces = array();

  /**
   * Creates a new Autoload instance that loads classes of the
   * specified namespaces.
   *
   * @return void
   */
  public function __construct()
  { }

  /**
   * Sets the namespace separator used by classes in the namespace of this class loader.
   *
   * @param string $sep
   * @return void
   */
  public function setNamespaceSeparator($separator)
  {
    $this->namespaceSeparator = $separator;
  }

  /**
   * Gets the namespace separator used by classes in the namespace of this class loader.
   *
   * @return string $namespaceSeparator.
   */
  public function getNamespaceSeparator()
  {
    return $this->namespaceSeparator;
  }

  /**
   * Sets the file extension of class files in the namespace of this class loader.
   *
   * @param string $fileExtension
   * @return void
   */
  public function setFileExtension($fileExtension)
  {
    $this->fileExtension = $fileExtension;
  }

  /**
   * Gets the file extension of class files in the namespace of this class loader.
   *
   * @return string $fileExtension
   */
  public function getFileExtension()
  {
    return $this->fileExtension;
  }

  /**
   * Gets all currently registered namespaces with associated include paths
   *
   * @return array $namespaces
   */
  public function getRegisteredNamespaces()
  {
    return $this->namespaces;
  }

  /*
   * Set the include path for a namespace and register the namespace in the class lookup
   *
   * @param string $ns
   * @param string $includePath
   * @return void
   */
  public function registerNamespace($namespace, $includePath = null)
  {
    $this->namespaces[$namespace] = $includePath;
  }

  /**
   * Set the include path for multiple namespaces and register the namespace in the
   * class lookup
   *
   * @param array $registrations
   * @return void
   */
  public function registerNamespaces(array $registrations)
  {
    foreach($registrations as $namespace => $includePath)
    {
      $this->registerNamespace($namespace, $includePath);
    }
  }

  /**
   * Installs this class loader on the SPL autoload stack.
   * Remove from autoloader stack with {@link unregister()}
   *
   * @see spl_autoload_register()
   * @return void
   */
  public function register()
  {
    spl_autoload_register(array($this, 'loadClass'));
  }

  /**
   * Uninstalls this class loader from the SPL autoloader stack.
   *
   * @see spl_autoload_unregister()
   * @return void
   */
  public function unregister()
  {
    spl_autoload_unregister(array($this, 'loadClass'));
  }

  /**
   * Loads the given class or interface.
   *
   * @param string $className
   * @return void
   */
  public function loadClass($className)
  {
    //@fixme: bad loop for namespaces
    foreach($this->namespaces as $namespace => $includePath)
    {
      if($namespace.$this->namespaceSeparator == substr($className, 0, strlen($namespace.$this->namespaceSeparator)))
      {
        if(false !== ($lastNsPos = strripos($className, $this->namespaceSeparator)))
        {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $this->fileExtension;

        $path = ($includePath !== null ? $includePath . DIRECTORY_SEPARATOR : __DIR__ . DIRECTORY_SEPARATOR) . $fileName;

        if(!file_exists($path))
        {
          throw new FileException(sprintf("File %s does not exist", $path));
        }

        require $path;

        return true;
      }
    }

    throw new ClassException(sprintf("Class or Interface '%s' not found.", $className));
  }
}
