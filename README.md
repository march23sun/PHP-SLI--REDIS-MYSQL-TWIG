PHP-SLIM-REDIS-MYSQL-TWIG
Restful API example

##Apache 


Add htaccess file  -> Projectfolder/.htaccess:
<code>
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . index.php [L]
</code>


## Composer
<code>php -r "readfile('https://getcomposer.org/installer');" | php </code>
