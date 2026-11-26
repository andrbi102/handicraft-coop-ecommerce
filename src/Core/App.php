<?php

declare(strict_types=1);

namespace App\Core;

use Autoloader;
use Container;
use Database;
use View;
use Logger;
use ErrorHandler;
use SecureSession;
use Csrf;
use Router;

/**
 * هسته برنامه — بوت‌استرپ، سیم‌کشی کانتینر، میان‌افزارها و دیسپچ روتر.
 *
 * تمام اجزای دست‌نویس scaffolds/ یک‌بار require می‌شوند (کلاس‌های سراسری
 * بدون namespace). سپس اتولودر، فضای‌نام App\ را به src/ نگاشت می‌کند.
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level core
 */
final class App
{
    public static Container $container;

    /** لیست scaffolds که کلاس‌های سراسری تعریف می‌کنند — به‌ترتیب وابستگی. */
    private const SCAFFOLDS = [
        'logger.php',
        'error-handler.php',
        'autoloader.php',
        'router.php',
        'container.php',
        'database.php',
        'session.php',
        'csrf.php',
        'validator.php',
        'view.php',
        'pipeline.php',
        'crypto.php',
        'db-migrate.php',
        'event-dispatcher.php',
        'event-store.php',
        'command-bus.php',
        'query-bus.php',
        'graph.php',
        'cache.php',
        'rate-limiter.php',
        'http-client.php',
        'tracing.php',
        'hmvc-dispatcher.php',
        'serverless-handler.php',
        'mcp-server.php',
        'worker-orchestrator.php',
        'humanizer.php',
        'web-fetch.php',
        'design-tokens.php',
    ];

    public static function boot(): Container
    {
        $base = dirname(__DIR__, 2);
        $support = $base . '/src/Support';

        // ۱) بارگذاری scaffolds — کلاس‌های سراسری (بدون namespace)
        foreach (self::SCAFFOLDS as $file) {
            require_once $support . '/' . $file;
        }

        // ۲) ثبت اتولودر برای فضای‌نام App\
        $loader = new Autoloader();
        $loader->addNamespace('App\\', $base . '/src');
        $loader->register();

        // ۳) کلید برنامه — پیش از هر استفاده از CSRF
        AppKey::ensure();

        // ۴) کانتینر وابستگی‌ها
        $c = new Container();
        self::$container = $c;

        $c->set(Logger::class, static fn (): Logger => new Logger(
            $base . '/storage/logs/app.log',
            Config::get('debug') ? 'debug' : 'warning',
        ));

        $c->set(Database::class, static function () use ($base): Database {
            $dbCfg = (array) Config::get('db', []);
            $db = new Database($dbCfg['dsn'], $dbCfg['user'], $dbCfg['pass']);
            // SQLite: فعال‌سازی کلیدهای خارجی و WAL برای همزمانی بهتر
            if ($db->isSqlite()) {
                $db->execute('PRAGMA foreign_keys = ON');
                $db->execute('PRAGMA journal_mode = WAL');
            }
            return $db;
        });

        $c->set(View::class, static fn (): View => new View($base . '/templates'));

        $c->set(Auth::class, static fn (Container $c): Auth => new Auth($c->get(Database::class)));

        // ۵) ثبت مدل‌ها و سرویس‌ها
        ServiceProvider::register($c);

        return $c;
    }

    /**
     * اجرای یک درخواست HTTP — تنظیم میان‌افزارها و دیسپچ.
     *
     * @param array<string, mixed> $server معادل $_SERVER
     */
    public static function run(array $server): void
    {
        $c = self::$container;
        $logger = $c->get(Logger::class);

        // مدیریت خطای سراسری — بدون افشای جزئیات در production.
        (new ErrorHandler($logger, (bool) Config::get('debug')))->register();

        // نشست امن (HttpOnly + Secure + SameSite=Strict)
        SecureSession::start();

        // هدرهای امنیتی — قبل از هر خروجی
        Middleware::securityHeaders();

        $router = new Router();

        // میان‌افزار ۱: لاگ هر درخواست
        $router->use(static function (array $ctx, callable $next) use ($logger, $server): void {
            $logger->info('{method} {path}', [
                'method' => $server['REQUEST_METHOD'] ?? 'CLI',
                'path' => $server['REQUEST_URI'] ?? '/',
            ]);
            $next($ctx);
        });

        // میان‌افزار ۲: تأیید CSRF برای درخواست‌های state-changing
        $router->use(static function (array $ctx, callable $next): void {
            $method = (string) ($ctx['method'] ?? '');
            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
                $token = $_POST['_csrf'] ?? null;
                if (!Csrf::verify(is_string($token) ? $token : null)) {
                    http_response_code(419);
                    exit('۴۱۹ — توکن CSRF نامعتبر است. صفحه را بازخوانی و دوباره تلاش کنید.');
                }
            }
            $next($ctx);
        });

        // میان‌افزار ۳: بارگذاری کاربر جاری و فلش‌ها در ctx
        $router->use(static function (array $ctx, callable $next) use ($c): void {
            $auth = $c->get(Auth::class);
            $ctx['auth'] = $auth;
            $ctx['user'] = $auth->user();
            $ctx['flash'] = [
                'success' => SecureSession::flash('success'),
                'error' => SecureSession::flash('error'),
                'info' => SecureSession::flash('info'),
            ];
            $next($ctx);
        });

        // تعریف مسیرها
        $loader = require dirname(__DIR__, 2) . '/routes/web.php';
        $loader($router, $c);

        $path = self::pathFrom($server);
        $method = strtoupper((string) ($server['REQUEST_METHOD'] ?? 'GET'));
        $router->dispatch($method, $path);
    }

    /** مسیر درخواست را بدون query string برمی‌گرداند. */
    private static function pathFrom(array $server): string
    {
        $uri = (string) ($server['REQUEST_URI'] ?? '/');
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        if ($path === '' || $path === false) {
            $path = '/';
        }
        $path = (string) $path;
        // رمزگشایی Percent-encoding برای پشتیبانی از slugهای فارسی. روتر
        // سگمنت‌ها را با اسلش جدا می‌کند؛ چون slugها (تولید Slug::make) هرگز
        // شامل «/» نیستند، رمزگشایی کل مسیر امن است و امکان تزریق مسیر نمی‌سازد.
        if ($path !== '/') {
            $path = rawurldecode($path);
        }
        return $path;
    }
}
