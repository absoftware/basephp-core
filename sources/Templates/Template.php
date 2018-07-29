<?php
/**
 * @project BasePHP Core
 * @file Template.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Templates;

interface Template
{
    function assign($name, $value): void;
    
    function fetch($templateFile): string;
    
    function display($templateFile): void;
    
    function fileExtension(): string;
}
