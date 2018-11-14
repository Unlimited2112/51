set /p name=< name.txt
git init
git add .
git commit -m "commit"
git remote add origin https://github.com/%name%.git
git push -u origin master
pause