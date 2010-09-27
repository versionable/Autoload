<?php

namespace Autoload\Exception;

/**
 * File exception thrown when namespace is registered but class file could
 * not be found.
 *
 * <code>
 * <?php
 *
 * include 'src/Autoload/Exception/FileException.php';
 *
 * use Autoload\Exception\FileException;
 *
 * throw new FileException('File not found');
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

class FileException extends AutoloadException
{
}