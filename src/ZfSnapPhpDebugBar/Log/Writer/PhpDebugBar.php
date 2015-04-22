<?php

namespace ZfSnapPhpDebugBar\Log\Writer;

use DebugBar\DataCollector\MessagesCollector;
use Zend\Log\Writer\AbstractWriter;

/**
 * PhpDebugBar
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class PhpDebugBar extends AbstractWriter
{
    /**
     * @var MessagesCollector
     */
    protected $messagesCollector;

    /**
     *
     * @param MessagesCollector $debugbar
     */
    public function __construct(MessagesCollector $debugbar)
    {
        $this->messagesCollector = $debugbar;
    }

    /**
     * @param array $event
     */
    protected function doWrite(array $event)
    {
        $priority = $this->priorityMap($event['priorityName']);
        $this->messagesCollector->addMessage($event['message'], $priority);
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
