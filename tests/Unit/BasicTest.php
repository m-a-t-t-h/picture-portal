<?php

namespace Tests\Unit;

use App\Services\ImgSrv;
use Tests\TestCase;

/**
 * @todo
 */
class BasicTest extends TestCase
{
    public function testResolvingHashToFilePath(): void
    {
        $hash = "7259fc1d03a6391ed1521cf1cecf4c09a692dd15bc8783c14dc2fe2c8f4a343e";
        $path = ImgSrv::hashToPath($hash);

        self::assertEquals("/Photos/2010/2010-06-24 - Farnborough Air Show/DSCF4560.JPG", $path);
    }
}
