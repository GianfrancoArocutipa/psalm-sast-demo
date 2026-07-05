name: SAST — Psalm Taint Analysis

# Runs static application security testing on every push and pull request.
# The build FAILS if Psalm detects a tainted data flow (SQLi, XSS, path
# traversal, etc.), so vulnerable code never reaches the main branch.

on:
  push:
    branches: [main]
  pull_request:
    branches: [main]

permissions:
  contents: read
  security-events: write   # needed to upload SARIF to GitHub Code Scanning

jobs:
  psalm-taint-analysis:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout repository
        uses: actions/checkout@v4

      - name: Set up PHP 8.2
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          coverage: none
          tools: composer

      - name: Install dependencies
        run: composer install --no-progress --prefer-dist

      - name: Run Psalm taint analysis (SARIF report)
        run: vendor/bin/psalm --taint-analysis --no-cache --report=results.sarif

      - name: Upload findings to GitHub Code Scanning
        if: always()   # upload the report even when the scan fails the build
        uses: github/codeql-action/upload-sarif@v3
        with:
          sarif_file: results.sarif
