<?php

namespace Weirin\Resource;

use Weirin\OSS\Core\OssException;
use Weirin\OSS\Model\ObjectListInfo;
use Weirin\OSS\Model\PrefixInfo;
use Weirin\OSS\OssClient;

/**
 * Class ServiceProviderOss
 * @package resource
 */
class ServiceProviderOss extends ServiceProviderAbstract
{
    /**
     * @var OssClient
     */
    private $client;
    /**
     * @var
     */
    private $bucket;
    /**
     * @var
     */
    private $endpoint;


    /**
     * ServiceProviderOss constructor.
     * @param $options
     */
    public function __construct($options)
    {
        $accessKeyId = $options['oss_access_key_id'];
        $accessKeySecret = $options['oss_access_key_secret'];
        $endpoint = $options['oss_endpoint'];

        $this->bucket = $options['oss_bucket'];
        $this->endpoint = $options['oss_endpoint'];
        $this->endpoint = 'https://' . $this->endpoint;

        try {
            $this->client= new OssClient($accessKeyId, $accessKeySecret, $endpoint ,true);
        } catch (OssException $e) {
            throw $e;
        }
    }

    /**
     * @param $path
     * @param $destPath
     * @return null
     */
    public function add($path, $destPath)
    {
        return $this->client->uploadFile($this->bucket, $destPath, $path);
    }

    /**
     * @param $content
     * @param $destPath
     * @return null
     */
    public function addContent($content, $destPath)
    {
        return $this->client->putObject($this->bucket, $destPath, $content);
    }

    /**
     * @param $path
     * @param null $style
     * @return string
     */
    public function getUrl($path, $style = null)
    {
        if ($style) {
            return $path ? $this->endpoint . '/' . $path . '!' . $style : $this->endpoint;
        } else {
            return $path ? $this->endpoint . '/' . $path : $this->endpoint;
        }
    }

    /**
     * @param $path
     */
    public function delete($path)
    {
        $this->client->deleteObject($this->bucket, $path);
    }

    /**
     * @param $oldPath
     * @param $newPath
     * @return bool|null
     */
    public function replace($oldPath, $newPath, $tmpPath)
    {
        // 这两个函数是否成功都返回null， By LCN
        $this->client->deleteObject($this->bucket, $oldPath);
        $this->client->uploadFile($this->bucket, $newPath, $tmpPath);
    }

    /**
     * @param $path
     * @return bool
     */
    public function exists($path)
    {
        return $this->client->doesObjectExist($this->bucket, $path);
    }

    /**
     * @param $path
     * @return bool
     */
    public function move($oldPath, $newPath)
    {
       $this->client->copyObject($this->bucket, $oldPath, $this->bucket, $newPath);
        $this->delete($oldPath);
    }

    /**
     * @param Array $paths
     */
    public function batchDelete($paths)
    {
        $this->client->deleteObjects($this->bucket, $paths);
    }

    /**
     * @param $path
     * @param $destPath
     * @return null
     */
    public function uploadDir($path, $destPath)
    {
        return $this->client->uploadDir($this->bucket, $destPath, $path, '.|..|.svn|.git', true);
    }

    /**
     * @param $path
     * @return null
     */
    public function addDir($path)
    {
        return $this->client->createObjectDir($this->bucket, $path);
    }

    /**
     * @param $path
     * @return null
     */
    public function getDirTree($dir = '')
    {

        $dirList = []; // 获取的目录列表, 数组的一阶表示分页结果
        $tree = [];

        while (true) {
            $options = [
                'prefix'    => $dir,
            ];
            try {
                $fileListInfo = $this->client->listObjects($this->bucket, $options);
                // 得到nextMarker, 从上一次 listObjects 读到的最后一个文件的下一个文件开始继续获取文件列表, 类似分页
            } catch (OssException $e) {
                throw $e;
            }
            $nextMarker = $fileListInfo->getNextMarker();
            $dirItem = $fileListInfo->getPrefixList();
            $dirList[] = $dirItem;
            if ($nextMarker === '') break;
        }

        foreach ($dirList[0] as $item){
            $tree[] = $this->buildTree($item);
        }

        return $tree;
    }

    private function buildTree($item)
    {
        $dirPath = $item->getPrefix();
        $dirName = basename($dirPath);
        return [
            'text' => $dirName,
            'children' => $this->getDirTree($dirPath)
        ];


    }


}