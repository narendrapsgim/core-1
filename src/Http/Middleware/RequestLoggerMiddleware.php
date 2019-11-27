<?php

namespace OpenDialogAi\Core\Http\Middleware;

use Closure;
use DateTime;
use Illuminate\Http\Request;
use OpenDialogAi\Core\LoggingHelper;
use OpenDialogAi\Core\RequestLog;
use OpenDialogAi\Core\ResponseLog;
use Symfony\Component\HttpFoundation\Response;

class RequestLoggerMiddleware
{
    private $requestId;

    /**
     * Set request ID.
     * @param $requestId
     */
    public function __construct($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Handle request termination.
     *
     * @param  Request $request
     * @param  Response $response
     */
    public function terminate(Request $request, Response $response)
    {
        $requestLength = microtime(true) - LARAVEL_START;
        $memoryUsage = memory_get_usage();

        $userId = LoggingHelper::getUserIdFromRequest();

        RequestLog::create([
            'user_id' => ($userId) ? $userId : '',
            'url' => $request->url(),
            'query_params' => json_encode($request->query()),
            'method' => $request->method(),
            'source_ip' => $request->ip(),
            'request_id' => $this->requestId,
            'raw_request' => json_encode($request->all()),
            'microtime' => DateTime::createFromFormat('U.u', LARAVEL_START)->format('Y-m-d H:i:s.u'),
        ])->save();

        ResponseLog::create([
            'user_id' => ($userId) ? $userId : '',
            'request_length' => $requestLength,
            'request_id' => $this->requestId,
            'memory_usage' => $memoryUsage,
            'http_status' => $response->getStatusCode(),
            'headers' => $response->headers,
            'raw_response' => $response->getContent()
        ])->save();
    }
}
