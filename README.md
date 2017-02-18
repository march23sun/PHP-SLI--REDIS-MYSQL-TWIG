# PHP-SLIM-REDIS-MYSQL-TWIG
Restful API example

##Start 

download Composer https://getcomposer.org/download/ and install


## Composer
```
php -r "readfile('https://getcomposer.org/installer');" | php 
```
```
php composer.phar create-project slim/slim-skeleton [my-app-name]
```


##Apache 

Add htaccess file  -> Projectfolder/.htaccess:
```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . index.php [L]
```


## Redis

Download and start Redis Server : https://github.com/dmajkic/redis/downloads
```
$ cd redis 
$ redis-server.exe redis.conf
```
Install php-redis ext on PHP
```
put php_redis.dll   -> ../php/ext
config php.ini add line -> extension=php_redis.dll
```
Restart Apache Server 
