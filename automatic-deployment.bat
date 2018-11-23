@REM ##########################################################################
@REM # PROG: TheLaraMan                                                       #
@REM # DATE: 2018-11-23                                                       #
@REM # GOAL: Automatic deployment and installation						      #
@REM # RUN : Run at the root folder of your laravel project	                  #
@REM # REF : http://www.iis.net/learn/get-started/getting-started-with-iis/getting-started-with-appcmdexe
@REM ##########################################################################
@ECHO off
@TITLE InvoiceNeko configuration tool

@REM ##########################################################################
@REM # Installing dependencies                                                #
@REM # INFO : add --ignore-platform-reqs if you get PHP error				  #
@REM ##########################################################################
@ECHO 1. Composer Install
@ECHO.
CALL composer install
@ECHO.

@REM ##########################################################################
@REM # Installing NPM                                                #
@REM ##########################################################################
@ECHO 2. NPM Install
@ECHO.
CALL npm install
@ECHO.

@REM ##########################################################################
@REM # Compile assets                                            			  #
@REM ##########################################################################
@ECHO 3. Compiling assets
@ECHO.
CALL npm run dev
@ECHO.

@REM ##########################################################################
@REM # Compying .env file                                         			  #
@REM ##########################################################################
@ECHO 4. Compying .env file   
@ECHO.
CALL cp .env.example .env
@ECHO.

@REM ##########################################################################
@REM # Generate key			                                      			  #
@REM ##########################################################################
@ECHO 5. Generating key
@ECHO.
CALL php artisan key:generate
@ECHO.

pause
