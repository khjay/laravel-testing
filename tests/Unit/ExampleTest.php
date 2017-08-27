<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_normalizes_a_string_for_the_cache_key()
    {
        $cache = $this->prophesize(RussianCache::class);
        $directive = new BladeDirective($cache->reveal());

        $cache->has('cache-key')->shouldBeCalled();

        $directive->setUp('cache-key');
    }

    /** @test */
    public function it_nomalizes_a_cacheable_model_for_the_cache_key()
    {
        $cache = $this->prophesize(RussianCache::class);
        $directive = new BladeDirective($cache->reveal());

        $cache->has('stub-cache-key')->shouldBeCalled();

        $directive->setUp(new ModelStub);
    }

    /** @test */
    public function it_nomalizes_an_array_for_the_cache_key()
    {
        $cache = $this->prophesize(RussianCache::class);
        $directive = new BladeDirective($cache->reveal());

        $item = ['foo', 'bar'];
        $cache->has(md5('foobar'))->shouldBeCalled();

        $directive->setUp($item);
    }
}

class BladeDirective
{
    protected $cache;

    public function __construct(RussianCache $cache)
    {
        $this->cache = $cache;
    }

    public function setUp($key)
    {
        $this->cache->has(
            $this->normalizeKey($key)
        );
    }

    protected function normalizeKey($item)
    {
        if (is_object($item) && method_exists($item, 'getCacheKey')) {
            return $item->getCacheKey();
        }

        if (is_array($item)) {
            return md5(implode($item));
        }

        return $item;
    }
}

class RussianCache
{
    public function has()
    {

    }
}

class ModelStub
{
    public function getCacheKey()
    {
        return 'stub-cache-key';
    }
}