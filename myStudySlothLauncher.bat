@echo off
setlocal
REM author Athanasios Tsagris 6/26/2024
REM the purpose of this bat file is to prepare the php configuration and run a webserver for the study sloth application.

REM Get the directory path of the batch script (where this batch file is located)
set "batch_dir=%~dp0"

REM Define paths and URLs
set "php_zip=%batch_dir%\etc\packages\php-8.0.30-Win32-vs16-x86.zip"
set "php_dir=%batch_dir%\etc\packages\php"
set "php_exe=%php_dir%\php.exe"
set "project_dir=%batch_dir%"
set "server_url=http://localhost:8000/etc/index.html"

REM Check if PHP is installed
if exist "%php_exe%" (
    echo PHP is already installed in: %php_exe%
) else (
    echo PHP is not installed. Extracting PHP from manual download...

    REM Check if PHP ZIP file exists
    if not exist "%php_zip%" (
        echo Error: PHP ZIP file not found. Make sure to download it manually.
        exit /b 1
    )

    REM Extract PHP
    powershell -Command "Expand-Archive -Path '%php_zip%' -DestinationPath '%php_dir%'"

    REM Check if extraction was successful
    if not exist "%php_exe%" (
        echo Error: Failed to extract PHP. Check the contents of '%php_zip%'.
        exit /b 1
    )

    echo PHP installed successfully in: %php_dir%
)

REM Start PHP built-in server
cd /d "%project_dir%"
start "" "%php_exe%" -S 0.0.0.0:8000

REM Open web browser
start "" "%server_url%"

pause
endlocal

