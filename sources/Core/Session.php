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
     * Domain for session.
     * @var string|null
     */
    protected $domain = null;

    /**
     * Session constructor.
     * @param int $sessionTime
     *      Session time in seconds.
     * @param string $domain
     *      Domain with dot at the beginning like ".example.com" will share session
     *      between all subdomains. Domain without dot at the beginning like "example.com",
     *      "subdomain.example.com" or null will limit session only to one specific domain.
     */
    public function __construct(int $sessionTime = 2400, string $domain = null)
    {
        ini_set('session.gc_maxlifetime', $sessionTime);
        session_set_cookie_params(0, '/', $domain);
        session_start();
        $this->sessionId = session_id();
        $this->domain = $domain;
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
    public function get(string $name)
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
    public function set(string $name, $value): void
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

    /**
     * Sets cookie.
     * @param string $name Name of cookie.
     * @param string $value Value of cookie.
     * @param int $lifeTime Life time of cookie.
     * @param bool $currentDomainOnly
     *      Allows to use cookie only for current domain
     *      even if session was created for wildcard domain.
     */
    public function setCookie(string $name, string $value, int $lifeTime = 31536000, bool $currentDomainOnly = false)
    {
        $domain = $currentDomainOnly ? null : $this->domain;
        setcookie($name, $value, time() + $lifeTime, '/', $domain);
    }
}
