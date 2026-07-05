# Psalm SAST Demo — Taint Analysis on a Vulnerable PHP App

Companion repository for the article **"Applying SAST to a PHP Application with Psalm's Taint Analysis"** (Dev.to).

A tiny "vehicle workshop" web app with intentionally vulnerable endpoints (SQL Injection, XSS, Path Traversal), analyzed with [Psalm](https://psalm.dev) — one of the open-source static analysis tools listed by [OWASP](https://owasp.org/www-community/Source_Code_Analysis_Tools).

> ⚠️ The code in `src/vulnerable/` is insecure **on purpose**. Never deploy it.

## Structure

```
src/vulnerable/   Insecure endpoints (what Psalm catches)
src/fixed/        Remediated versions (what Psalm approves)
.github/workflows/sast-psalm.yml   CI: taint analysis on every push/PR
psalm.xml         Psalm configuration
```

## Run it locally

```bash
composer install
vendor/bin/psalm --taint-analysis --no-cache
# or simply:
composer sast
```

Expected result: Psalm reports `TaintedSql`, `TaintedHtml` and `TaintedFile` errors pointing at `src/vulnerable/`, tracing each flow from `$_GET` to the dangerous sink.

## CI automation

Every push and pull request triggers the **SAST — Psalm Taint Analysis** workflow:

1. Installs PHP 8.2 + Composer dependencies.
2. Runs `psalm --taint-analysis` and exports a SARIF report.
3. Uploads the report to **GitHub Code Scanning** (Security tab), so findings appear as annotations directly on the pull request.
4. Fails the build if any tainted flow is found — vulnerable code cannot be merged.

## Author

Franco Arocutipa — Systems Engineering, Universidad Privada de Tacna (EPIS)
Team Nadir Systems
