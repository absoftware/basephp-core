<?php
/**
 * @project BasePHP Core
 * @file BaseView.php created by Ariel Bogdziewicz on 26/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

use Base\Data\Data;

/**
 * Base class for all views.
 */
abstract class BaseView
{
    /**
     * Returns data to display.
     * @return Data
     */
    abstract public function get(): Data;
}
