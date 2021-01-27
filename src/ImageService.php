<?php

namespace Weirin\Resource;

/**
 * Class ImageService
 * @package resource
 */
class ImageService
{
    /** @var **/
    protected $serviceProvider;

    /** @var string **/
    public $name = 'OSS';

    /** @var array **/
    public $options = [];

    /**
     * @param $name
     * @param $options
     */
    public function __construct($name = 'OSS', $options)
    {
        if('OSS' == $this->name){
            $this->options = $options;
            $serviceProvider = new ServiceProviderOss($this->options);
            $this->setServiceProvider($serviceProvider);
        }
    }

    /**
     * @param $path
     * @param $destPath
     * @return mixed
     */
    public function setServiceProvider(ServiceProviderAbstract $serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;
    }

    /**
     * @param $path
     * @param $destPath
     * @return mixed
     */
    public function add($path, $destPath)
    {
        return $this->serviceProvider->add($path, $destPath);
    }

    /**
     * @param $content
     * @param $destPath
     * @return mixed
     */
    public function addContent($content, $destPath)
    {
        return $this->serviceProvider->addContent($content, $destPath);
    }

    /**
     * @param $path
     * @param null $style
     * @return mixed
     */
    public function getUrl($path, $style = null)
    {
        return $this->serviceProvider->getUrl($path, $style);
    }

    /**
     * @param $path
     * @return mixed
     */
    public  function delete($path)
    {
        return $this->serviceProvider->delete($path);
    }

    /**
     * @param $oldPath
     * @param $newPath
     * @return mixed
     */
    public  function replace($oldPath, $newPath, $tmpPath)
    {
        return $this->serviceProvider->replace($oldPath, $newPath, $tmpPath);
    }

    /**
     * @param $path
     * @return mixed
     */
    public  function exists($path)
    {
        return $this->serviceProvider->exists($path);
    }

    /**
     * @param $oldPath
     * @param $newPath
     * @return mixed
     */
    public  function move($oldPath, $newPath)
    {
        return $this->serviceProvider->move($oldPath, $newPath);
    }

    /**
     * @param $paths
     * @return mixed
     */
    public  function batchDelete($paths)
    {
        return $this->serviceProvider->batchDelete($paths);
    }

    /**
     * @param $path
     * @param $destPath
     * @return mixed
     */
    public function uploadDir($path, $destPath)
    {
        return $this->serviceProvider->uploadDir($path, $destPath);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function addDir($path)
    {
        return $this->serviceProvider->addDir($path);
    }

    /**
     * @param $dir
     * @return mixed
     */
    public function getDirTree($dir = '')
    {
        return $this->serviceProvider->getDirTree($dir);
    }
}