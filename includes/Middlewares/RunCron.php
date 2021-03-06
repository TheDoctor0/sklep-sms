<?php

namespace App\Middlewares;

use App\Application;
use App\CronExecutor;
use App\Settings;
use Symfony\Component\HttpFoundation\Request;

class RunCron implements MiddlewareContract
{
    public function handle(Request $request, Application $app)
    {
        $settings = $app->make(Settings::class);

        if ($settings['cron_each_visit']) {
            /** @var CronExecutor $cronExecutor */
            $cronExecutor = $app->make(CronExecutor::class);
            $cronExecutor->run();
        }
    }
}
