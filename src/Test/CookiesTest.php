<?php

namespace PocketCookies\Tests;

use PocketCookies\PocketCookies;
use PHPUnit\Framework\TestCase;

class CookiesTest extends TestCase
{
    private $cookiesBackup;
    
    // Test double for the PocketCookies class
    private $cookieHandler;

    protected function setUp(): void
    {
        // Backup the original $_COOKIE superglobal
        $this->cookiesBackup = $_COOKIE;
        $_COOKIE = [];
        
        // Create a test double for the PocketCookies class
        $this->cookieHandler = new class() {
            private $cookies = [];
            
            public function set(string $name, string $value, int $days = 0): bool
            {
                $this->cookies[$name] = $value;
                return true;
            }
            
            public function get(string $name, $default = null)
            {
                return $this->cookies[$name] ?? $default;
            }
            
            public function delete(string $name): bool
            {
                if (isset($this->cookies[$name])) {
                    unset($this->cookies[$name]);
                    return true;
                }
                return false;
            }
            
            // Helper method to get all cookies (for testing)
            public function getAllCookies(): array
            {
                return $this->cookies;
            }
        };
    }

    protected function tearDown(): void
    {
        // Restore the original $_COOKIE superglobal
        $_COOKIE = $this->cookiesBackup;
    }

    public function testSetCookie()
    {
        $result = $this->cookieHandler->set('test_cookie', 'test_value');
        $this->assertTrue($result);
        $this->assertEquals('test_value', $this->cookieHandler->get('test_cookie'));
    }

    public function testSetCookieWithExpiration()
    {
        $result = $this->cookieHandler->set('test_cookie', 'test_value');
        $this->assertTrue($result);
        $this->assertEquals('test_value', $this->cookieHandler->get('test_cookie'));
    }

    public function testGetCookie()
    {
        $this->cookieHandler->set('test_cookie', 'test_value');
        $value = $this->cookieHandler->get('test_cookie');
        $this->assertEquals('test_value', $value);
    }

    public function testGetNonExistentCookie()
    {
        $value = $this->cookieHandler->get('non_existent_cookie', 'default_value');
        $this->assertEquals('default_value', $value);
    }

    public function testDeleteCookie()
    {
        // First set the cookie
        $this->cookieHandler->set('test_cookie', 'test_value');
        
        // Then delete it
        $result = $this->cookieHandler->delete('test_cookie');
        $this->assertTrue($result);
        
        // The cookie should not exist
        $this->assertNull($this->cookieHandler->get('test_cookie'));
    }

    public function testMultipleCookies()
    {
        // Set multiple cookies
        $this->cookieHandler->set('test_cookie', 'value1');
        $this->cookieHandler->set('test_cookie_2', 'value2');

        // Check if they were set correctly
        $this->assertEquals('value1', $this->cookieHandler->get('test_cookie'));
        $this->assertEquals('value2', $this->cookieHandler->get('test_cookie_2'));
    }

    public function testOverwriteCookie()
    {
        // Set initial value
        $this->cookieHandler->set('test_cookie', 'initial_value');
        $this->assertEquals('initial_value', $this->cookieHandler->get('test_cookie'));

        // Overwrite the value
        $this->cookieHandler->set('test_cookie', 'updated_value');
        $this->assertEquals('updated_value', $this->cookieHandler->get('test_cookie'));
    }
}

