<?php

namespace Hermes\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Hermes\WebBundle\Controller\BaseController;
use Hermes\Common\ArrayToolkit;


class FileController extends BaseController
{
    public function uploadAction(Request $request)
    {
        $file = $request->files->get('file');

        $directory = $this->getDirectory();

        $record = $this->getFileService()->uploadFile($file, $directory);

        return new JsonResponse($record);
    }

    private function getDirectory()
    {
        return $this->container->getParameter('upload.public_directory');
    }

    private function getFileService()
    {
        return $this->biz['file_service'];
    }
}
