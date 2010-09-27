<?php

namespace Autoload\Exception;

/**
 * Base exception class for Autoload
 *
 * <code>
 * <?php
 *
 * include 'src/Autoload/Exception/AutoloadException.php';
 *
 * use Autoload\Exception\AutoloadException;
 *
 * throw new AutloadException('General Error');
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
class AutoloadException extends \RunTimeException
{
}