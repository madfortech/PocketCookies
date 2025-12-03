<?php

namespace PocketCookies\Tests;
require __DIR__ . '/../vendor/autoload.php';
use PocketCookies\CookieManager;
use PocketTesting\Testing; // <-- PocketTesting ka class

class CookiesTest extends Testing   // <--- IMPORTANT
{
    public function run()
    {
        $cookie = new CookieManager();

        $result = $cookie->getCookieText();

        $this->checkEqual(
            "Hello from PocketCookies!", 
            $result, 
            "Cookie text check"
        );
    }

    
}

$test = new CookiesTest();
$test->run();
