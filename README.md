Installation
------------
Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```
Example data is stored in example.php file

Tax rate is stored in .env file

Usage
------------

There are 3 commands, one for each question from assensment.

This command prints data from example.php file.
```
bin/console sourcetoad:print ./example.php
```
This command sorts data from example.php by given keys. There is no limit for keys as long as they are comma separated.
```
bin/console sourcetoad:sort ./example.php account_limit,room_no
```
This command is for creating and displaying Cart instance. It has built in menu for all actions, you can use them in any order you like.
```
bin/console sourcetoad:cart
```
Why Symfony?
------------
Symfony is very flexible and allows to create huge apps, small microservices and command applications. So I think it is perfect solution for lot of tasks.
I have used symfony 5.4 for this test because it it latest LTS version.
