@echo off

rem -------------------------------------------------------------
rem  Yii command line install script for Windows.
rem
rem  @author Pavel Kamir <czechcamus@gmail.com>
rem -------------------------------------------------------------

@setlocal

set YII_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

"%PHP_COMMAND%" "%YII_PATH%yii" "install" "--appconfig=%YII_PATH%private/console/config/main.php" %*
"%PHP_COMMAND%" "%YII_PATH%yii" "migrate" %*

@endlocal
