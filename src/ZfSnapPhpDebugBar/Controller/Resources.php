<?php

namespace ZfSnapPhpDebugBar\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Resources
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class Resources extends AbstractActionController
{
    protected $extensionToContentTypeMap = array(
        'css' => 'text/css; charset=UTF-8',
        'js' => 'text/javascript; charset=UTF-8',
    );

    public function indexAction()
    {
        return $this->prepareAssetResponse(__DIR__.'/../../../../../maximebf/debugbar/src/DebugBar/Resources/');
    }

    public function customAction()
    {
        return $this->prepareAssetResponse(__DIR__.'/../../../assets/');
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
