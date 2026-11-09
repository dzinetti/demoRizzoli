if type python > /dev/null 2>&1 ; then
    echo "python trovato"
    python -m venv venv
elif type python3 > /dev/null 2>&1 ; then
    echo "python3 trovato"
    python3 -m venv venv
fi

if [ -d "venv/bin" ]; then
    echo "activate e all'interno della cartella /bin/"
    source venv/bin/activate
elif [ -d "venv/Scripts" ]; then
    echo "activate e all'interno della cartella /Scripts/"
    source venv/Scripts/activate
fi

pip install -r requirements.txt
