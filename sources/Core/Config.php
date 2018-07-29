<?php
/**
 * @project BasePHP Core
 * @file Config.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

/**
 * Interface Config.
 * @package Base\Core
 */
interface Config
{
    /**
     * Session time in seconds.
     * @return int
     */
    function sessionTime(): int;

    /**
     * Configuration of ports for normal and secure connections.
     * @return Ports
     */
    function ports(): Ports;
}
