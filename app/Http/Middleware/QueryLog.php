<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Log;

/**
 * クエリログを出力します。
 * 
 * @author Kazuki_Kamizuru
*/
class QueryLog
{
    /**
     * ハンドラ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // デバッグモードでない場合は出力しない
        if (!env('APP_DEBUG')) {
            return $next($request);
        }

        $this->readyOutputLog();

        $response = $next($request);

        $this->outputLog();

        return $response;
    }

    /**
     * クエリログを有効化します。
     *
     * @return void
     */
    private function readyOutputLog()
    {
        foreach (config('database.connections') as $dbName => $settings) {
            DB::connection($dbName)->enableQueryLog();
        }
    }

    /**
     * クエリログの出力を行います。
     *
     * @return void
     */
    private function outputLog()
    {
        foreach (config('database.connections') as $dbName => $settings) {
            $logs = DB::connection($dbName)->getQueryLog();
            if (empty($logs)) {
                continue;
            }
            Log::debug("Database: [{$settings['database']}]");
            foreach ($logs as $log) {
                if (!($outputQuery = $log['query'] ?? '')) {
                    continue;
                }
                $bindings = implode(',', ($log['bindings'] ?? ''));
                Log::debug("Query: [${outputQuery}], bindings: [${bindings}]");
            }
        }
    }
}
