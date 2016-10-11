<?php 
namespace Hermes\Biz\File;

interface FileService
{
    public function uploadFile($file, $directory);
}