<?php
namespace Base\Core\Responses;

interface Response
{
    /**
     * Returns data which represents response.
     * If something goes wrong it will throw exception.
     */
    function get();
    
    /**
     * Renders output into output buffer.
     * If something goes wrong it will throw exception.
     */
    function output();
}
