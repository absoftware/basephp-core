<?php
namespace Base\Templates;

interface Template
{
    function assign($name, $value): void;
    
    function fetch($templateFile): string;
    
    function display($templateFile): void;
    
    function fileExtension(): string;
}
