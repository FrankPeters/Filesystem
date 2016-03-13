<?php

namespace Mosaic\Filesystem\Adapters\Flysystem;

use Aws\S3\S3Client;
use BadMethodCallException;
use Dropbox\Client;
use Interop\Container\Definition\DefinitionProviderInterface;
use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Dropbox\DropboxAdapter;
use Mosaic\Common\Conventions\FolderStructureConvention;
use Mosaic\Filesystem\Providers\FlystemProvider;

final class Component implements \Mosaic\Common\Components\Component
{
    /**
     * @var FolderStructureConvention
     */
    private $folderStructure;

    /**
     * @var DiskResolverCollection
     */
    private $diskResolvers;

    /**
     * @var array
     */
    private static $custom = [];

    /**
     * @param FolderStructureConvention $folderStructure
     */
    public function __construct(FolderStructureConvention $folderStructure)
    {
        $this->diskResolvers   = new DiskResolverCollection;
        $this->folderStructure = $folderStructure;

        $this->local('local', $this->folderStructure->storagePath());
    }

    /**
     * @return DefinitionProviderInterface[]
     */
    public function getProviders() : array
    {
        return [
            new FlystemProvider($this->getDiskResolvers())
        ];
    }

    /**
     * @param  string   $name
     * @param  callable $resolver
     * @return $this
     */
    public function disk(string $name, callable $resolver)
    {
        $this->diskResolvers->add($name, $resolver);

        return $this;
    }

    /**
     * @param  string $name
     * @param  string $path
     * @return $this
     */
    public function local(string $name, string $path)
    {
        $this->disk($name, function () use ($path) {
            return new Local($path);
        });

        return $this;
    }

    /**
     * @param  array $settings
     * @return $this
     */
    public function ftp(array $settings)
    {
        $this->disk('ftp', function () use ($settings) {
            return new Ftp($settings);
        });

        return $this;
    }

    /**
     * @param  string $bucket
     * @param  array  $settings
     * @return $this
     */
    public function aws(string $bucket, array $settings)
    {
        $this->disk('aws', function () use ($bucket, $settings) {

            $client = S3Client::factory($settings);

            return new AwsS3Adapter($client, $bucket);
        });

        return $this;
    }

    /**
     * @param  string $accessToken
     * @param  string $appSecret
     * @return $this
     */
    public function dropbox(string $accessToken, string $appSecret)
    {
        $this->disk('dropbox', function () use ($accessToken, $appSecret) {
            return new DropboxAdapter(
                new Client($accessToken, $appSecret)
            );
        });

        return $this;
    }

    /**
     * @param string   $name
     * @param callable $callback
     */
    public static function extend(string $name, callable $callback)
    {
        self::$custom[$name] = $callback;
    }

    /**
     * @return DiskResolverCollection
     */
    public function getDiskResolvers()
    {
        return $this->diskResolvers;
    }

    /**
     * @param  string    $name
     * @param  array     $params
     * @return Component
     */
    public function __call(string $name, array $params = [])
    {
        if (isset(self::$custom[$name])) {
            return $this->disk($name, self::$custom[$name]);
        }

        throw new BadMethodCallException;
    }
}
