<?php

namespace ZfSnapPhpDebugBar\View\Helper;

use DebugBar\JavascriptRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadLink;
use Zend\View\Helper\HeadScript;

/**
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class DebugBar extends AbstractHelper
{
    /**
     * @var JavascriptRenderer
     */
    protected $renderer;

    protected $customStyle;

    /**
     * @param JavascriptRenderer $renderer
     */
    public function __construct(JavascriptRenderer $renderer, $customStyle)
    {
        $this->renderer = $renderer;
        $this->customStyle = $customStyle;
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
     * @return HeadScript
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
     * @return HeadLink
     */
    public function appendStyles()
    {
        $styles = $this->renderer->getAssets('css');
        $headLink = $this->getView()->headLink();

        foreach ($styles as $style) {
            $headLink->appendStylesheet($style);
        }
        $headLink->appendStylesheet($this->getView()->url('phpdebugbar-custom-resource', ['resource' => $this->customStyle]));
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
