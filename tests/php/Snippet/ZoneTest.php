<?php

declare(strict_types=1);

namespace Bolt\Tests\Snippet;

use Bolt\Snippet\Zone;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tightenco\Collect\Support\Collection;

class ZoneTest extends TestCase
{
    public function providerZone()
    {
        $o = new \ReflectionClass(Zone::class);
        $constants = (new Collection(array_keys($o->getConstants())))
            ->filter(function ($v) {
                return mb_strpos($v, 'WIDGET') === false && mb_strpos($v, 'NOWHERE') === false;
            })
            ->map(function ($v) {
                return [$v];
            });

        return $constants->toArray();
    }

    public function testGet(): void
    {
        $request = Request::createFromGlobals();

        $this->assertSame('nowhere', Zone::get($request));
    }

    /**
     * @dataProvider providerZone
     */
    public function testZone(string $constant): void
    {
        $request = Request::createFromGlobals();

        Zone::set($request, $constant);

        $this->assertSame($constant, Zone::get($request));
        $this->assertTrue(Zone::is($request, $constant));
    }
}
