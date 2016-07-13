<?php

namespace ZfSnapPhpDebugBar\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Resources
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class Resources extends AbstractActionController
{
    /**
     * @var ServiceLocatorInterface
     * @since 0.11.2
     */
    protected $serviceLocator;

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

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return static
     * @since 0.11.2
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * @return ServiceLocatorInterface
     * @since 0.11.2
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
