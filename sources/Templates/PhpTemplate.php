<?php
/**
 * @project BasePHP Core
 * @file PhpTemplate.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Templates;

/**
 * Class PhpTemplate.
 * @package Base\Templates
 */
class PhpTemplate implements Template
{
    /**
     * Collection of template variables.
     * @var array
     */
    protected $variables = [];

    /**
     * Assigns template variable.
     * @param string $name
     * @param mixed $value
     */
    public function assign(string $name, $value): void
    {
        $this->variables[$name] = $value;
    }

    /**
     * Renders template and returns result as string.
     * @param string $templateFile
     * @return string
     */
    public function fetch(string $templateFile): string
    {
        extract($this->variables);
        ob_start();
        include($templateFile);
        return ob_get_clean();
    }

    /**
     * Renders template and puts result into output buffer.
     * @param string $templateFile
     */
    public function display(string $templateFile): void
    {
        echo $this->fetch($templateFile);
    }

    /**
     * Returns extension of filename for template files.
     * @return string
     */
    public function fileExtension(): string
    {
        return ".tpl.php";
    }
}
