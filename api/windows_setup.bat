@echo off
WHERE >nul 2>nul python
if %ERRORLEVEL% == 0 (
    echo python trovato
    python -m venv venv
    if exist venv\Scripts (
        call .\venv\Scripts\activate.bat
        pip install -r requirements.txt
        goto :eof
    )
)

WHERE >nul 2>nul python3
if %ERRORLEVEL% == 0 (
    echo python3 trovato
    python3 -m venv venv
    if exist venv\Scripts (
        call .\venv\Scripts\activate.bat
        pip install -r .\requirements.txt
        goto :eof
    )
)

echo Python non trovato o ambiente virtuale non creato.
