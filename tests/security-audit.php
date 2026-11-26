<?php

declare(strict_types=1);

/**
 * ممیزی امنیتی ایستا — اسکن همه فایل‌های .php پروژه.
 * اجرا: php tests/security-audit.php   (خروجی ۰ = PASS)
 *
 * معادل PHP برای scripts/audit.py. قواعد از SPEC php-zero-dep.
 *
 * @package Coop\Tests
 * @version 0.1.0
 * @security-level test
 */

$root = dirname(__DIR__);
$failed = 0;

function finding(string $file, int $line, string $issue): void
{
    global $failed;
    echo "  FAIL  {$file}:{$line} — {$issue}\n";
    $failed++;
}

$forbidden = ['eval(', 'shell_exec(', 'system(', 'passthru(', 'proc_open(', 'popen('];

/** @return array<int, string> */
function gather(string $root): array
{
    $dirs = ['src', 'public', 'scripts', 'routes', 'templates'];
    $files = [];
    foreach ($dirs as $d) {
        $base = $root . '/' . $d;
        if (!is_dir($base)) {
            continue;
        }
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $f) {
            if ($f->isFile() && $f->getExtension() === 'php') {
                $rel = str_replace($root . '/', '', $f->getPathname());
                // scaffolds کپی‌شده و فایل‌های تست skill را دور می‌زنیم
                if (str_starts_with($rel, 'src/Support/') || str_starts_with($rel, 'tests/scaffold-')) {
                    continue;
                }
                $files[] = $f->getPathname();
            }
        }
    }
    return $files;
}

$targets = gather($root);
foreach ($targets as $file) {
    $lines = file($file) ?: [];
    $rel = str_replace($root . '/', '', $file);
    $head = implode('', array_slice($lines, 0, 5));
    if (!str_contains($head, 'declare(strict_types=1)')) {
        finding($rel, 1, 'missing declare(strict_types=1)');
    }
    $isSrc = str_starts_with($rel, 'src/') && !str_starts_with($rel, 'src/Support/');
    if ($isSrc) {
        $blob = implode('', array_slice($lines, 0, 40));
        foreach (['@package', '@version', '@security-level'] as $tag) {
            if (!str_contains($blob, $tag)) {
                finding($rel, 1, "missing {$tag} in PHPDoc");
            }
        }
    }
    foreach ($lines as $n => $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '*') || str_starts_with($trimmed, '//') || str_starts_with($trimmed, '#') || str_starts_with($trimmed, '|')) {
            continue;
        }
        if (preg_match('/composer/i', $line)) {
            finding($rel, $n + 1, 'composer reference (Zero-Composer violated)');
        }
        if (preg_match('/SELECT \*/i', $line)) {
            finding($rel, $n + 1, 'SELECT * forbidden');
        }
        foreach ($forbidden as $fn) {
            if (str_contains($line, $fn)) {
                finding($rel, $n + 1, "forbidden function {$fn}");
            }
        }
        // exec( با مرز واژه‌ای — ->exec و curl_exec و shell_exec مجازند
        if (preg_match('/(?<![\\w>])exec\(/', $line)) {
            finding($rel, $n + 1, 'forbidden function exec(');
        }
        if (preg_match('/\bmd5\s*\(/', $line)) {
            finding($rel, $n + 1, 'md5( forbidden — use Argon2id');
        }
        if (preg_match('/\bsha1\s*\(/', $line)) {
            finding($rel, $n + 1, 'sha1( forbidden — use Argon2id');
        }
    }
}

echo $failed === 0
    ? "\nSECURITY AUDIT: PASS (" . count($targets) . " files scanned)\n"
    : "\nSECURITY AUDIT: {$failed} FINDING(S)\n";
exit($failed === 0 ? 0 : 1);
