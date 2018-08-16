<?php
/**
 * @project BasePHP Core
 * @file Template.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Templates;

/**
 * Interface for template engine.
 * @package Base\Templates
 */
interface Template
{
    /**
     * Assigns template variable.
     * @param string $name
     * @param mixed $value
     */
    function assign(string $name, $value): void;

    /**
     * Renders template and returns result as string.
     * @param string $templateFile
     * @return string
     */
    function fetch(string $templateFile): string;

    /**
     * Renders template and puts result into output buffer.
     * @param string $templateFile
     */
    function display(string $templateFile): void;

    /**
     * Returns extension of filename for template files.
     * For example ".tpl.php" for PHP templates or ".tpl" for Smarty.
     * @return string
     */
    function fileExtension(): string;
}
