<?php
/**
 * @project BasePHP Core
 * @file BaseSession.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Core;

/**
 * Keeps state of the session.
 */
class BaseSession
{
    /**
     * Session identifier.
     * @var string
     */
    protected string $sessionId;

    /**
     * Session time in seconds.
     * @var int
     */
    protected int $sessionTime = 3600;

    /**
     * Domain for session.
     * @var ?string
     */
    protected ?string $domain;

    /**
     * Session constructor.
     * @param int $sessionTime
     *      Session time in seconds.
     * @param string|null $domain
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
    public function sessionId(): string
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
        return $_SESSION[$name] ?? null;
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

    /**
     * Sets cookie.
     * @param string $name Name of cookie.
     * @param string $value Value of cookie.
     * @param int $lifetime Lifetime of cookie.
     * @param bool $currentDomainOnly
     *      Allows using cookie only for current domain
     *      even if session was created for wildcard domain.
     */
    public function setCookie(string $name, string $value, int $lifetime = 31536000, bool $currentDomainOnly = false): void
    {
        $domain = $currentDomainOnly ? null : $this->domain;
        setcookie($name, $value, time() + $lifetime, '/', $domain);
    }
}
