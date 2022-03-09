<?php

require_once __DIR__ . '/../src/init.php';

use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{
    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    public function testGet()
    {
        $_GET = ['foo' => '1', 'bar' => '2', 'baz' => '3'];

        $this->assertEquals('1', HTTP::get('foo'));
        $this->assertEquals(null, HTTP::get('404'));
        $this->assertEquals('default', HTTP::get('404', 'default'));

        $this->assertEquals(['foo' => '1', 'bar' => '2'], HTTP::get(['foo', 'bar']));
        $this->assertEquals(['foo' => '1', '404' => null], HTTP::get(['foo', '404']));
        $this->assertEquals(['foo' => '1', '404' => 'default'], HTTP::get(['foo', '404'], 'default'));
        $this->assertEquals(['foo' => '1', 'bar' => '2', 'baz' => '3'], HTTP::get());
    }

    public function testPost()
    {
        $_POST = ['foo' => '1', 'bar' => '2', 'baz' => '3'];

        $this->assertEquals('1', HTTP::post('foo'));
        $this->assertEquals(null, HTTP::post('404'));
        $this->assertEquals('default', HTTP::post('404', 'default'));

        $this->assertEquals(['foo' => '1', 'bar' => '2'], HTTP::post(['foo', 'bar']));
        $this->assertEquals(['foo' => '1', '404' => null], HTTP::post(['foo', '404']));
        $this->assertEquals(['foo' => '1', '404' => 'default'], HTTP::post(['foo', '404'], 'default'));
        $this->assertEquals(['foo' => '1', 'bar' => '2', 'baz' => '3'], HTTP::post());
    }

    public function testSession()
    {
        $_SESSION = ['foo' => '1', 'bar' => '2', 'baz' => '3'];

        $this->assertEquals('1', HTTP::session('foo'));
        $this->assertEquals(null, HTTP::session('404'));
        $this->assertEquals('default', HTTP::session('404', 'default'));

        $this->assertEquals(['foo' => '1', 'bar' => '2'], HTTP::session(['foo', 'bar']));
        $this->assertEquals(['foo' => '1', '404' => null], HTTP::session(['foo', '404']));
        $this->assertEquals(['foo' => '1', '404' => 'default'], HTTP::session(['foo', '404'], 'default'));
        $this->assertEquals(['foo' => '1', 'bar' => '2', 'baz' => '3'], HTTP::session());
    }
}
