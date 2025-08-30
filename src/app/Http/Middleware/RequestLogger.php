<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

final class RequestLogger
{
    private array $excludes = [
        '_debugbar',
    ];

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (config('logging.request.enable')) {
            if ($this->isWrite($request)) {
                $this->write($request);
            }
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isWrite(Request $request): bool
    {
        return !in_array($request->path(), $this->excludes, true);
    }

    /**
     * @param Request $request
     */
    private function write(Request $request): void
    {
        $this->logger->debug($request->method(), ['url' => $request->fullUrl(), 'request' => $request->all()]);
    }
}
