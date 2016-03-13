<?php

namespace Mosaic\Filesystem\Tests\Adapters\Flysystem;

use BadMethodCallException;
use Closure;
use League\Flysystem\Adapter\Local;
use Mosaic\Common\Conventions\DefaultFolderStructure;
use Mosaic\Filesystem\Adapters\Flysystem\Component;
use Mosaic\Filesystem\Adapters\Flysystem\Rackspace\Container;
use Mosaic\Filesystem\Providers\FlystemProvider;

class ComponentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Component
     */
    protected $component;

    public function setUp()
    {
        $this->component = new Component(new DefaultFolderStructure(__DIR__));
    }

    public function test_it_adds_local_disk_by_default()
    {
        $adapter = $this->component->getDiskResolvers()->get('local');

        $this->assertInstanceOf(Local::class, $adapter());
    }

    public function test_can_get_component_providers()
    {
        foreach ($this->component->getProviders() as $provider) {
            $this->assertInstanceOf(FlystemProvider::class, $provider);
        }
    }

    public function test_can_add_another_local_disk()
    {
        $this->component->local('public', __DIR__ . '/storage');

        $adapter = $this->component->getDiskResolvers()->get('public');

        $this->assertInstanceOf(Local::class, $adapter());
    }

    public function test_can_add_disk()
    {
        $this->component->disk('custom', function () {
            return 'customResolved';
        });

        $adapter = $this->component->getDiskResolvers()->get('custom');

        $this->assertInstanceOf(Closure::class, $adapter);
        $this->assertEquals('customResolved', $adapter());
    }

    public function test_can_add_ftp_disk()
    {
        $this->component->ftp([]);

        $adapter = $this->component->getDiskResolvers()->get('ftp');

        $this->assertInstanceOf(Closure::class, $adapter);
    }

    public function test_can_add_aws_disk()
    {
        $this->component->aws('bucket', []);

        $adapter = $this->component->getDiskResolvers()->get('aws');

        $this->assertInstanceOf(Closure::class, $adapter);
    }

    public function test_can_add_dropbox_disk()
    {
        $this->component->dropbox('token', 'secret');

        $adapter = $this->component->getDiskResolvers()->get('dropbox');

        $this->assertInstanceOf(Closure::class, $adapter);
    }

    public function test_can_add_memory_disk()
    {
        $this->component->memory();

        $adapter = $this->component->getDiskResolvers()->get('memory');

        $this->assertInstanceOf(Closure::class, $adapter);
    }

    public function test_can_add_memory_disk_with_custom_name()
    {
        $this->component->memory('customMemoryDisk');

        $adapter = $this->component->getDiskResolvers()->get('customMemoryDisk');

        $this->assertInstanceOf(Closure::class, $adapter);
    }

    public function test_can_add_rackspace_disk()
    {
        $container = (new Container('container', 'endpoint', 'username', 'password'))
            ->setObjectStoreLocation('LON')
            ->setObjectStoreName('cloudFiles')
            ->setOptions(['customOption' => true]);

        $this->component->rackspace($container, 'customRackspace');

        $adapter = $this->component->getDiskResolvers()->get('customRackspace');

        $this->assertInstanceOf(Closure::class, $adapter);
    }

    public function test_can_add_extensions()
    {
        Component::extend('foo', function () {
            return 'bar';
        });

        $this->component->foo();

        $adapter = $this->component->getDiskResolvers()->get('foo');

        $this->assertEquals('bar', $adapter());
    }

    public function test_cannot_add_non_existing_disk()
    {
        $this->expectException(BadMethodCallException::class);

        $this->component->false();
    }
}
