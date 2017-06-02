<?php

namespace App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Laravel\Lumen\Application;

/**
 * カスタムアプリケーションクラス
 *
 * @author  Kazuki Kamizuru
 */
class MyApplication extends Application
{
    /**
     * {@inheritdoc}
     */
    protected function getMonologHandler()
    {
        $log = env('LOG_DIR', storage_path('logs/')).'web-api.log';
        return (new StreamHandler($log, Logger::DEBUG))
                            ->setFormatter(new LineFormatter(null, null, true, true));
    }
}
