<?php

namespace Weirin\Resource;

/**
 * Class ServiceProvider
 * @package resource
 */
abstract class ServiceProviderAbstract
{
    /**
     * @param $path
     * @param $destPath
     * @return mixed
     */
    abstract public function add($path, $destPath);

    /**
     * @param $path
     * @param null $style
     * @return mixed
     */
    abstract public function getUrl($path, $style = null);

    /**
     * @param $path
     * @return mixed
     */
    abstract public function delete($path);

    /**
     * @param $oldPath
     * @param $newPath
     * @return mixed
     */
    abstract function replace($oldPath, $newPath, $tmpPath);

    /**
     * @param $path
     * @return mixed
     */
    abstract public function exists($path);

    /**
     * @param $path
     * @return bool
     */
    abstract public function move($oldPath, $newPath);

    /**
     * @param $path
     * @return mixed
     */
    abstract public function batchDelete($paths);
}