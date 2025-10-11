@echo off
echo Opening MFW Events Dashboard...
echo.
echo Correct URLs:
echo - Main Platform: http://localhost:3000/
echo - SuperAdmin Dashboard: http://localhost:3000/SuperAdmin-dashboard/index.html
echo - Login: http://localhost:3000/login.html
echo - Navigation Helper: http://localhost:3000/navigation.html
echo.
echo Opening SuperAdmin Dashboard...
start "" "http://localhost:3000/SuperAdmin-dashboard/index.html"
echo.
echo Opening Navigation Helper in 3 seconds...
timeout /t 3 /nobreak >nul
start "" "http://localhost:3000/navigation.html"