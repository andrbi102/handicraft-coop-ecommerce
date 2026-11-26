#!/usr/bin/env python3
r"""
ممیزی امنیتی ایستا — معادل Python برای tests/security-audit.php.
اسکن همه فایل‌های .php پروژه (به‌جز scaffolds که ممیزی مهارت پوشش می‌دهد).

قواعد (از SPEC php-zero-dep):
 ۱. هر فایل declare(strict_types=1) در ۵ خط اول.
 ۲. هر فایل src/ PHPDoc با @package, @version, @security-level.
 ۳. بدون ارجاع composer (Zero-Composer).
 ۴. بدون SELECT * در کد production.
 ۵. بدون توابع ممنوع: eval, shell_exec, system, passthru, proc_open, popen,
    و exec (مگر بعد از > یا \w — تا ->exec و curl_exec و shell_exec مجاز
    بمانند؛ shell_exec جداگانه گرفته می‌شود).
 ۶. بدون md5(/sha1( (هش رمز فقط Argon2id).

خروجی ۰ = PASS.
"""
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent

# مسیرهای اسکن: همه .php به‌جز کپی scaffolds در src/Support (که ممیزی مهارت
# پوشش می‌دهد) و خود tests/.
SCAN_DIRS = ["src", "public", "scripts", "routes", "templates"]
SKIP_FILES = {
    "src/Support",          # scaffolds کپی‌شده — ممیزی skill پوشش می‌دهد
    "tests/scaffold-smoke.php",
    "tests/scaffold-security-audit.php",
}

FORBIDDEN = ["eval(", "shell_exec(", "system(", "passthru(", "proc_open(", "popen("]
# exec( با مرز واژه‌ای: مگر بعد از > یا \w
EXEC_RE = re.compile(r"(?<![\w>])exec\(")
MD5_RE = re.compile(r"\bmd5\s*\(")
SHA1_RE = re.compile(r"\bsha1\s*\(")
SELECT_STAR_RE = re.compile(r"SELECT\s+\*", re.IGNORECASE)
COMPOSER_RE = re.compile(r"composer", re.IGNORECASE)

findings = []


def is_comment_line(stripped: str) -> bool:
    return (stripped.startswith("*")
            or stripped.startswith("//")
            or stripped.startswith("#")
            or stripped.startswith("|"))


def php_files():
    for d in SCAN_DIRS:
        base = ROOT / d
        if not base.exists():
            continue
        for p in base.rglob("*.php"):
            rel = str(p.relative_to(ROOT))
            if any(rel.startswith(s) or rel == s for s in SKIP_FILES):
                continue
            yield p, rel


def audit():
    for path, rel in php_files():
        try:
            lines = path.read_text(encoding="utf-8").splitlines()
        except Exception as e:
            findings.append((rel, 0, f"read error {e}"))
            continue

        # ۱. strict_types در ۵ خط اول
        head = "\n".join(lines[:5])
        if "declare(strict_types=1)" not in head:
            findings.append((rel, 1, "missing declare(strict_types=1)"))

        is_src = rel.startswith("src/") and not rel.startswith("src/Support/")
        is_template = rel.startswith("templates/")
        # ۲. PHPDoc سه‌فیلدی برای src/ (templates سبک‌تر — فقط strict_types)
        if is_src:
            blob = "\n".join(lines[:40])
            for tag in ("@package", "@version", "@security-level"):
                if tag not in blob:
                    findings.append((rel, 1, f"missing {tag} in PHPDoc"))

        for n, line in enumerate(lines, start=1):
            stripped = line.strip()
            if is_comment_line(stripped):
                continue
            # ۳. composer
            if COMPOSER_RE.search(line) and not stripped.startswith("*"):
                findings.append((rel, n, "composer reference (Zero-Composer violated)"))
            # ۴. SELECT *
            if SELECT_STAR_RE.search(line):
                findings.append((rel, n, "SELECT * forbidden"))
            # ۵. توابع ممنوع
            for fn in FORBIDDEN:
                if fn in line:
                    findings.append((rel, n, f"forbidden function {fn}"))
            if EXEC_RE.search(line):
                findings.append((rel, n, "forbidden function exec("))
            # ۶. md5/sha1
            if MD5_RE.search(line):
                findings.append((rel, n, "md5( forbidden — use Argon2id"))
            if SHA1_RE.search(line):
                findings.append((rel, n, "sha1( forbidden — use Argon2id"))


def main():
    audit()
    scanned = sum(1 for _ in php_files())
    if not findings:
        print(f"\nSECURITY AUDIT: PASS ({scanned} files scanned)\n")
        sys.exit(0)
    print(f"\nSECURITY AUDIT: {len(findings)} FINDING(S)\n")
    for rel, line, issue in findings:
        print(f"  FAIL  {rel}:{line} — {issue}")
    sys.exit(1)


if __name__ == "__main__":
    main()
