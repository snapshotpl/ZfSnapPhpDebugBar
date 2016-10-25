<?php

namespace ZfSnapPhpDebugBar\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class Resources extends AbstractActionController
{
    protected $extensionToContentTypeMap = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'text/javascript; charset=UTF-8',
    ];

    private $debugbarResourcesPath;
    private $customResourcesPath;

    public function __construct($debugbarResourcesPath, $customResourcesPath)
    {
        $this->debugbarResourcesPath = $debugbarResourcesPath;
        $this->customResourcesPath = $customResourcesPath;
    }

    public function indexAction()
    {
        return $this->prepareAssetResponse($this->debugbarResourcesPath);
    }

    public function customAction()
    {
        return $this->prepareAssetResponse($this->customResourcesPath);
    }

    protected function prepareAssetResponse($path)
    {
        $resource = $this->params('resource');

        $filePath = $path.$resource;

        if (!file_exists($filePath)) {
            return $this->notFoundAction();
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        $contentType = $extension = $this->mapExtenstionToContentType($extension);

        $this->response->getHeaders()->addHeaderLine('Content-Type', $contentType);

        $contents = file_get_contents($filePath);

        return $this->getResponse()->setContent($contents);
    }

    protected function mapExtenstionToContentType($extension)
    {
        if (isset($this->extensionToContentTypeMap[$extension])) {
            return $this->extensionToContentTypeMap[$extension];
        }
        return 'text/html; charset=UTF-8';
    }
}
