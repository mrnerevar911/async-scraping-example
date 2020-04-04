##Description
This is an async scraping example written in PHP. It just scrapes 10 users from JSONPlaceholder API asynchronously.

##Installation
1. Clone repository.
2. Run `composer install`.
3. Prepare your MySQL database with schema below and change connection settings in the script.
4. Run `php index.php`.

##DB Schema
`create table users
(
    id       int auto_increment
        primary key,
    name     varchar(50) null,
    username varchar(50) null,
    email    varchar(50) null
);`
