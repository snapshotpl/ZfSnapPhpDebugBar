<?php

namespace ZfSnapPhpDebugBar\Log\Writer;

use DebugBar\DebugBar;
use Zend\Log\Writer\AbstractWriter;

/**
 * PhpDebugBar
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class PhpDebugBar extends AbstractWriter
{
    /**
     * @var DebugBar
     */
    protected $debugbar;

    /**
     *
     * @param DebugBar $debugbar
     */
    public function __construct(DebugBar $debugbar)
    {
        $this->debugbar = $debugbar;
    }

    /**
     * @param array $event
     */
    protected function doWrite(array $event)
    {
        $priority = $this->priorityMap($event['priorityName']);
        $this->debugbar['messages']->addMessage($event['message'], $priority);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function priorityMap($name)
    {
        $name = strtolower($name);
        $map = array(
            'warn' => 'warning',
            'err' => 'error',
        );
        if (isset($map[$name])) {
            $name = $map[$name];
        }
        return $name;
    }

}
