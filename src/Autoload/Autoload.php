<?php

namespace Autoload;

/**
 * Autoloader based on the SplClassLoader implementation that implements the
 * technical interoperability standards for PHP 5.3 namespaces and class names.
 *
 *     // Example which loads classes for the Doctrine Common package and Symfony in the
 *     // Doctrine\Common namespace and Symfony namespace.
 *     $classLoader = new Autoload();
 *     $classLoader->registerNamespace('Doctrine\Common', '/path/to/doctrine');
 *     $classLoader->registerNamespace('Symfony', '/path/to/symfony');
 *     $classLoader->register();
 *
 *     // Example which loads classes for the Doctrine Common package and Symfony in the
 *     // Doctrine\Common namespace and Symfony namespace. (Uses new method to group registrations)
 *     $classLoader = new Autoload();
 *     $classLoader->registerNamespaces(array(
 *       'Doctrine\Common' => '/path/to/doctrine',
 *       'Symfony' => '/path/to/symfony',
 *     ));
 *     $classLoader->register();
 *
 * @author Harry Walter <harry.walter@lqdinternet.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Roman S. Borschel <roman@code-factory.org>
 * @author Matthew Weier O'Phinney <matthew@zend.com>
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class Autoload
{
  private $namespaceSeparator = '\\';
  private $fileExtension = '.php';
  private $namespaces = array();

  /**
   * Creates a new <tt>Autoload</tt> that loads classes of the
   * specified namespaces.
   *
   * @return void
   */
  public function __construct()
  { }

  /**
   * Sets the namespace separator used by classes in the namespace of this class loader.
   *
   * @param string $sep The separator to use.
   * @return void
   */
  public function setNamespaceSeparator($sep)
  {
    $this->namespaceSeparator = $sep;
  }

  /**
   * Gets the namespace separator used by classes in the namespace of this class loader.
   *
   * @return string $namespaceSeperator.
   */
  public function getNamespaceSeperator()
  {
    return $this->namespaceSeperator;
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
  public function registerNamespace($ns, $includePath = null)
  {
    $this->namespaces[$ns] = $includePath;
  }

  /**
   * Set the include path for multiple namespaces and register the namespace in the
   * class lookup
   *
   * @param array $registrations
   * @return void
   */
  public function registerNamespaces(array $registratons)
  {
    foreach($registrations as $namespace => $includePath)
    {
      $this->registerNamespace($namespace, $includePath);
    }
  }

  /**
   * Installs this class loader on the SPL autoload stack.
   *
   * @return void
   */
  public function register()
  {
    spl_autoload_register(array($this, 'loadClass'));
  }

  /**
   * Uninstalls this class loader from the SPL autoloader stack.
   *
   * @return void
   */
  public function unregister()
  {
    spl_autoload_unregister(array($this, 'loadClass'));
  }

  /**
   * Loads the given class or interface.
   *
   * @param string $className The name of the class to load.
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
          throw new Exception("File ($path) does not exist");
        }

        require $path;

        return true;
      }
    }

    throw new Exception("Class ($className) or Interface not found.");
  }
}
