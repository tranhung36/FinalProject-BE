set up:  
1 . vào env set up database :  
    DB_CONNECTION=mysql (loại csdl)  
    DB_HOST=127.0.0.1 (host mặc định 127.0.0.1)  
    DB_PORT=3306 (port của host)  
    DB_DATABASE=easyteams (tên csdl)  
    DB_USERNAME=root (username)  
    DB_PASSWORD=1234 (password)  
2.  cp .env.example .env  
3.  php artisan key:generate  
4. php artisan migrate  
5. php artisan passport:install
6. php artisan serve  
