# P6_Openclassroom
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9ee3de9b9dbe448cb7fa6698a3fc93f8)](https://www.codacy.com/manual/lionneclement/P6-Openclassroom?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=lionneclement/P6-Openclassroom&amp;utm_campaign=Badge_Grade)
## Prerequisite 
1) Php
2) MySQL
3) Composer
4) Nodejs
5) Apache

Use Wamp, Xampp or Lamp

## Clone
1) Make a clone with `https://github.com/lionneclement/P6-Openclassroom.git` and cd P6-Openclassroom
2) Install composer with `composer install`
3) Create database with `php bin/console doctrine:database:create`
4) Create table with `php bin/console doctrine:schema:create`
5) Create data with `php bin/console doctrine:fixtures:load`

   By default a user was created with email=admin@gmail.com and password=password
   
6) Run server with `bin/console server:run` or `symfony server:start`
   
   Go to localhost with port 8000
   
 Normally everything works, If you have a error or send me an mail to lionneclement@gmail.com or create a issue
