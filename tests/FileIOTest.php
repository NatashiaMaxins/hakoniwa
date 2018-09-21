<?php

declare(strict_types=1);
error_reporting(E_ALL);
setlocale(LC_ALL, "ja_JP.UTF-8");

require_once __DIR__."/../config.php";

use \PHPUnit\Framework\TestCase;
use \Hakoniwa\Model\FileIO;

$init = new \Hakoniwa\Init;

final class FileIOTest extends TestCase
{
    private $mock;
    private $class;
    private $rootpath;

    public function setUp(): void
    {
        $this->mock = $this->getMockForTrait(FileIO::class);
        $this->class = new \ReflectionClass($this->mock);
    }

    // public function testMkfile(): void
    // {
    //     $path = sys_get_temp_dir().DIRECTORY_SEPARATOR."/tests".time();
    //     $this->rootpath = $path;
    //     mkdir($path, 0777, true);

    //     $method = $this->class->getMethod("mkfile");
    //     $method->setAccessible(true);

    //     // $this->assertTrue($method->invoke($this->mock, $path."/test1.txt"));
    //     // $this->assertTrue($method->invoke($this->mock, $path."/test2.dat"));
    //     // $this->assertTrue($method->invoke($this->mock, $path."/test3/test3.log"));
    //     // $this->assertFileExists($path."/test1.txt");
    //     // $this->assertFileExists($path."/test2.dat");
    //     // $this->assertFileExists($path."/test3/test3.log");


    // }

    /**
     * @dataProvider asset4ParsePath
     */
    public function testParsePath($expected, $var_path): void
    {
        $method = $this->class->getMethod("parse_path");
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke($this->mock, $var_path));
    }

    public function asset4ParsePath()
    {
        yield "/foo/bar/baz" => ["/foo/bar/baz", "/foo/bar/baz"];
        yield "/foo/bar//baz" => ["/foo/bar/baz", "/foo/bar//baz"];
        yield "/hoge/fuga/xxxx/../piyo" => ["/hoge/fuga/piyo", "/hoge/fuga/xxxx/../piyo"];
        yield "/hoge/xxxx/xxxx/../../piyo" => ["/hoge/piyo", "/hoge/xxxx/xxxx/../../piyo"];
        yield "/xxxx/../../hoge" => ["/hoge", "/xxxx/../../hoge"];
        yield "/qwerty/asdfgh/./zxcvbn" => ["/qwerty/asdfgh/zxcvbn", "/qwerty/asdfgh/./zxcvbn"];
    }

    // /**
    //  * @dataProvider asset4Rimraf
    //  */
    // public function testRimraf(string $path): void
    // {
    //     $this->assertTrue($this->mock->rimraf($path));
    // }

    // public function asset4Rimraf()
    // {
    //     global $init;

    //     $tmpdir = realpath(sys_get_temp_dir()."/test/");
    //     $pwd    = realpath(__DIR__."/../{$init->dirName}/test/");
    //     $b = false;

    //     if (!is_dir($tmpdir)) {
    //         $b = mkdir($tmpdir, 0755, true);
    //     }
    //     if ($b) {
    //         file_put_contents($tmpdir."test1.txt", "hogefuga");
    //         file_put_contents($tmpdir."test2.dat", "hogefuga");
    //         file_put_contents($tmpdir."test3.ini", "hogefuga");
    //     }
    //     if (!is_dir($pwd)) {
    //         $b = mkdir($pwd, 0755, true);
    //     }
    //     if ($b) {
    //         file_put_contents($pwd."test1.txt", "hogefuga");
    //         file_put_contents($pwd."test2.dat", "hogefuga");
    //         file_put_contents($pwd."test3.ini", "hogefuga");
    //     }

    //     yield "tmpdir" => $tmpdir;
    //     yield "save folder" => $pwd;
    // }
}
