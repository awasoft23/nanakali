:LOOP
tasklist /FI "IMAGENAME eq mysqld.exe" | find /I "mysqld.exe"
IF ERRORLEVEL 1 (
    echo MySQL is not running, waiting...
    timeout /t 5
    goto LOOP
) ELSE (
    echo MySQL is running, starting Laravel server...
    cd path\to\your\laravel\project
    php artisan serve
)
