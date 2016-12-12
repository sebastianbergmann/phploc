<?php

/*
 * Tests to verify current reports against ones from official PHAR.
 */
class PHPLOC_FunctionalTest extends PHPUnit_Framework_TestCase
{
    const DIR = __DIR__.'/../tests_functional';

    public static function setUpBeforeClass()
    {
        is_dir(self::DIR) || mkdir(self::DIR);
        chdir(self::DIR);

        copy('https://phar.phpunit.de/phploc.phar', 'phploc.phar');
        chmod('phploc.phar', 0777);

        $packages = [
            'https://github.com/laravel/laravel/archive/v5.3.16.zip',
            'https://github.com/Seldaek/monolog/archive/1.22.0.zip',
            'https://github.com/sebastianbergmann/phploc/archive/3.0.1.zip',
            'https://github.com/sebastianbergmann/phpunit/archive/5.7.3.zip',
            'https://github.com/symfony/console/archive/v3.2.0.zip',
        ];

        $zip = new ZipArchive();
        foreach ($packages as $url) {
            $file = basename($url);
            copy($url, $file);
            $zip->open($file);
            $zip->extractTo('.');
            $zip->close();
        }
    }

    public static function tearDownAfterClass()
    {
        shell_exec('rm -fr '.self::DIR);
    }

    public function directoriesProvider()
    {
        return [
            ['laravel-5.3.16'],
            ['monolog-1.22.0'],
            ['phploc-3.0.1'],
            ['phpunit-5.7.3'],
            ['console-3.2.0'],
        ];
    }

    /**
     * @dataProvider directoriesProvider
     */
    public function testRepository($directory)
    {
        $officialOutput = shell_exec('php phploc.phar --log-csv=official.csv --log-xml=official.xml '.$directory);

        $newOutput = preg_replace(
            '/phploc 3.0.1([a-z0-9\-]+) by Sebastian Bergmann./',
            'phploc 3.0.1 by Sebastian Bergmann.',
            shell_exec('php ../phploc --log-csv=new.csv --log-xml=new.xml '.$directory)
        );

        $this->assertSame($officialOutput, $newOutput);
        $this->assertSame(file_get_contents('official.csv'), file_get_contents('new.csv'));
        $this->assertSame(file_get_contents('official.xml'), file_get_contents('new.xml'));
    }
}
