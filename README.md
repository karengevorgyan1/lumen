# lumen
git clone https://github.com/karengevorgyan1/lumen/tree/master

copy .env.example and insert .env file

install composer

//run project
php -S localhost:8000 -t public

//run migartion and passport

php artisan migrate
php artisan passport:install


insert fake data

php artisan tinker
App\Models\User::factory()->count(5);
App\Models\Project::factory()->count(5);

open postman insert passport key and test it
