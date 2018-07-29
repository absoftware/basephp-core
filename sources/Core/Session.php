<?php
/**
 * @project BasePHP Core
 * @file Session.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

/**
 * Class Session.
 * @package Base\Core
 */
class Session
{
    /**
     * Session identifier.
     * @var string
     */
    protected $sessionId;

    /**
     * Session time in seconds.
     * @var int
     */
    protected $sessionTime = 3600;

    /**
     * Session constructor.
     * @param int $sessionTime Session time in seconds.
     * @param string $domain Specify here domain if you want share session between subdomains of given domain.
     */
    public function __construct(int $sessionTime, string $domain = null)
    {
        ini_set('session.gc_maxlifetime', $sessionTime);
        session_set_cookie_params(0, '/', $domain ? "." . $domain : null);
        session_start();
        $this->sessionId = session_id();
    }

    /**
     * Session identifier.
     * @return string
     */
    public function sessionId()
    {
        return $this->sessionId;
    }

    /**
     * Closes session.
     */
    public function close(): void
    {
        session_write_close();
    }

    /**
     * Gets session variable.
     * @param string $name
     * @return mixed
     */
    public function get(string $name): mixed
    {
        if (isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
        else
        {
            return null;
        }
    }

    /**
     * Sets session variable.
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Check existence of session variable.
     * @param string $name
     * @return bool
     */
    public function isset(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    /**
     * Destroys session variable.
     * @param string $name
     */
    public function unset(string $name): void
    {
        unset($_SESSION[$name]);
    }
}
