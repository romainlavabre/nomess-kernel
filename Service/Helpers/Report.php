<?php


namespace NoMess\Service\Helpers;


trait Report
{

    /**
     * @param string $message
     * @param string $filename
     */
    public function report(string $message, string $filename = ROOT . 'App/var/log/log.txt'): void
    {
        file_put_contents($filename, $message . '_________________________________________________________________' . "\r", FILE_APPEND);
    }
}