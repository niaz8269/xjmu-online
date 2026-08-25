<?php

namespace Psr\Log;

/**
 * Minimal PSR-3 logger interface shim for static analysis compatibility.
 */
interface LoggerInterface
{
    public function emergency($message, array $context = array());

    public function alert($message, array $context = array());

    public function critical($message, array $context = array());

    public function error($message, array $context = array());

    public function warning($message, array $context = array());

    public function notice($message, array $context = array());

    public function info($message, array $context = array());

    public function debug($message, array $context = array());

    public function log($level, $message, array $context = array());
}
