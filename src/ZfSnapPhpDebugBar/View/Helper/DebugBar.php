<?php

namespace ZfSnapPhpDebugBar\View\Helper;

use Zend\View\Helper\AbstractHelper;
use DebugBar\JavascriptRenderer;

/**
 * DebugBar
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DebugBar extends AbstractHelper
{
    /**
     * @var JavascriptRenderer
     */
    protected $renderer;

    /**
     * @param JavascriptRenderer $renderer
     */
    public function __construct(JavascriptRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return void
     */
    public function appendAssets()
    {
        $this->appendScripts();
        $this->appendStyles();
    }

    /**
     * @return Zend\View\Helper\HeadScript
     */
    public function appendScripts()
    {
        $scripts = $this->renderer->getAssets('js');
        $headScript = $this->getView()->headScript();

        foreach ($scripts as $script) {
            $headScript->appendFile($script);
        }
        return $headScript;
    }

    /**
     * @return Zend\View\Helper\HeadLink
     */
    public function appendStyles()
    {
        $styles = $this->renderer->getAssets('css');
        $headLink = $this->getView()->headLink();

        foreach ($styles as $style) {
            $headLink->appendStylesheet($style);
        }
        return $headLink;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->renderer->render();
    }

    /**
     * @return string
     */
    public function renderHead()
    {
        return $this->renderer->renderHead();
    }

}
