<?php
namespace Base\Core;

class Session
{
    //! Session identifier.
    protected $sessionId = null;
    
    public function __construct()
    {
        //ini_set('session.gc_maxlifetime', $config->sessionTime());
        //session_set_cookie_params(0, '/', "." . Request::getInstance()->domainHost());
        session_start();
        $this->sessionId = session_id();
    }
    
    public function sessionId()
    {
        return $this->sessionId;
    }
    
    public function close(): void
    {
        session_write_close();
    }
    
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
    
    public function set(string $name, mixed $value): void
    {
        $_SESSION[$name] = $val;
    }
    
    public function isset(string $name): bool
    {
        return isset($_SESSION[$name]);
    }
    
    public function unset(string $name): void
    {
        unset($_SESSION[$name]);
    }
}
