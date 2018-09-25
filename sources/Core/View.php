<?php
/**
 * @project BasePHP Core
 * @file View.php created by Ariel Bogdziewicz on 26/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Data\Data;

/**
 * Base class for all views.
 * @package Base\Core
 */
abstract class View
{
    /**
     * View constructor.
     */
    public function __construct()
    {
    }

    /**
     * Returns data to display.
     * @return Data
     */
    abstract function get(): Data;
}
