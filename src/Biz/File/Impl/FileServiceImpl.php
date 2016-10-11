<?php 
namespace Hermes\Biz\File\Impl;

use Hermes\Biz\File\FileService;
use Codeages\Biz\Framework\Service\KernelAwareBaseService;

class FileServiceImpl extends KernelAwareBaseService implements FileService
{
    private $biz;

    public function __construct($biz)
    {
        $this->biz = $biz;
    }

    public function uploadFile($file, $directory)
    {
        $record['uri'] = $this->generateUri($file, $directory);
        $record['size'] = $file->getSize();
        $record['created'] = time();
        $record = $this->getFileDao()->create($record);
        $record['file'] = $this->saveFile($file, $record['uri'], $directory);

        return $record;
    }

    private function saveFile($file, $uri, $directory)
    {
        $parsed = $this->parseFileUri($uri, $directory);

        if(!is_writable($directory)) {
            throw new \Exception("文件路径{$directory}不可写，文件上传失败");
        }

        $directory .= '/' . $parsed['directory'];

        return $file->move($directory, $parsed['name']);
    }

    private function parseFileUri($uri, $directory)
    {
        $parsed['directory'] = dirname($uri);
        $parsed['name'] = basename($uri);

        $parsed['fullPath'] = $directory . '/' . $uri;

        return $parsed;
    }

    private function generateUri($file)
    {
        $filename = $file->getClientOriginalName();
        $filenameParts = explode('.', $filename);
        $ext = array_pop($filenameParts);

        $uri = 'img/' . date('Y') . '/' . date('m-d') . '/' . date('His');
        $uri .= substr(uniqid(), -6) . substr(uniqid('', true), -6);
        $uri .= '.' . $ext;
        return $uri;
    }

    private function getFileDao()
    {
        return $this->biz['file_dao'];
    }
}