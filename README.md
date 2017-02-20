# PHP-SLIM-REDIS-MYSQL-TWIG
Restful API example


##Start 

download Composer https://getcomposer.org/download/ and install


## Composer
```
$ php -r "readfile('https://getcomposer.org/installer');" | php 
```
```
$ php composer.phar create-project slim/slim-skeleton [my-app-name]
```


##TWIG
```
$ composer require slim/twig-view
```

##Apache 

Add htaccess file  -> Projectfolder/.htaccess:
```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . index.php [L]
```

#MySql Schema

```
CREATE TABLE `member` (
  `idmember` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idmember`)
) 
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
config php.ini add line -> extension=php_openssl.dll
```
Restart Apache Server 

## DEMO API
Check Redis Server connection / disconnect (ping)
```
/CHECK
```
[mySQL]Query Mysql table data
```
/DB
```
[redis]sets the value at the specified key
```
/SET/{KEY}/{VALUE}
```
[redis]Gets the value of a key 
```
/GET/{KEY}
```
[redis]List all key in redis 
```
/KEYS
```
[redis]List all key and value  (json formatter)
```
/ALL
```
Restful API Render TO TWIG files
```
/PAGE/{ID}
```
exchange Mysql / Redis example 
```
/GETUSER/{ID}
```



