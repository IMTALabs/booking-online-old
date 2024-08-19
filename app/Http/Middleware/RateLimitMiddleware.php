<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next, $maxUpdates = 6, $maxDeletes = 1, $decayMinutes = 1)
    {
        $key = $request->ip();

        // Tên cache key để lưu trữ thông tin attempts
        $cacheKey = 'rate_limit:' . $key;

        // Lấy thông tin attempts từ cache
        $attempts = Cache::get($cacheKey, []);

        // Lọc và chỉ lấy các attempts trong 1 phút gần nhất
        $now = time();
        $filteredAttempts = array_filter($attempts, function ($attempt) use ($now, $decayMinutes) {
            return isset($attempt['time']) && $attempt['time'] >= ($now - $decayMinutes * 60);
        });

        // Đếm số lần update và delete đã thực hiện trong 1 phút
        $updatesInLastMinute = count(array_filter($filteredAttempts, function ($attempt) {
            return isset($attempt['update']) && $attempt['update'];
        }));

        $deletesInLastMinute = count(array_filter($filteredAttempts, function ($attempt) {
            return isset($attempt['delete']) && $attempt['delete'];
        }));

        info("Số lần xóa trong 1 phút: $deletesInLastMinute");
        info("Số lần update trong 1 phút: $updatesInLastMinute");

        // Kiểm tra xem có quá nhiều yêu cầu update hay delete chưa
        if ($updatesInLastMinute >= $maxUpdates) {
            return response()->json([
                'message' => 'Thực hiện quá nhiều lần update trong 1 phút, vui lòng đợi.',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        if ($deletesInLastMinute >= $maxDeletes) {
            return response()->json([
                'message' => 'Thực hiện quá nhiều lần delete trong 1 phút, vui lòng đợi.',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }
        $action = $request->method() === 'DELETE' ? 'delete' : 'update';

        // Nếu không có quá nhiều yêu cầu, thêm lần thực hiện mới vào cache
        $attempts[] = ['time' => $now, $action => true];

        Cache::put($cacheKey, $attempts, $decayMinutes * 60);

        return $next($request);
    }
}
