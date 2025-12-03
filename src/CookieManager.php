<?php
namespace PocketCookies;

class CookieManager
{

    public function getCookieText()
    {
        return "Hello from PocketCookies!";
    }

    /**
     * Set a secure cookie with basic options
     * @param string $name Cookie name
     * @param mixed $value Cookie value
     * @param int $days Days until expiration (0 = session cookie)
     * @return bool
     */
    public static function set($name, $value, $days = 0)
    {
        $options = [
            'expires'  => $days ? time() + (86400 * $days) : 0,
            'path'     => '/',
            'secure'   => true,    // Send only over HTTPS
            'httponly' => true,    // Not accessible via JavaScript
            'samesite' => 'Lax'    // Basic CSRF protection
        ];
        
        return setcookie($name, $value, $options);
    }

    /**
     * Get a cookie value
     * @param string $name Cookie name
     * @param mixed $default Default value if cookie doesn't exist
     * @return mixed
     */
    public static function get($name, $default = null)
    {
        return $_COOKIE[$name] ?? $default;
    }

    /**
     * Delete a cookie
     * @param string $name Cookie name
     * @return bool
     */
    public static function delete($name)
    {
        return setcookie($name, '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
}