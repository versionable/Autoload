<?php

namespace Autoload\Exception;

/**
 * Class exception thrown if namespace or class not registered.
 *
 * <code>
 * <?php
 *
 * include 'src/Autoload/Exception/ClassException.php';
 *
 * use Autoload\Exception\ClassException;
 *
 * throw new ClassException('Namespace not registered');
 *
 * ?>
 * </code>
 *
 * @author Harry Walter <harry@versionable.co.uk>
 *
 * @package Autoload
 * @subpackage Exception
 * @filesource
 */

class ClassException extends AutoloadException
{
}