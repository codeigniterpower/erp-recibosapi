

### BASE DE DATOS

Laravel puede crear la DB pero no es lo indicado, ya que el usuario 
configurado solo debve tener acceso a la DB permitida, por ende este 
proyecto debe tener un usuario ya existente y una DB ya existente para 
conectarse, esta esta definida en el archvo `.env.localhost`
del proyecto.

Dado es un sistema de storage simple, no reuse datos viejos, la
estructura de base de datos se codifico con data types equivalentes 
en ODBC sin problemas en la migraciones.

### WEBSERVER

Desafortunadamente laravel es ADD por ende en lso webservers depende 
de un dominio, en desarrollo se empleara `api.local` mientras:

Para que esto funcione debe tener un dns interno apuntando "api.local" a 127.0.0.1

* lighttpd: la configuracion puede ser insertada en una seccion server 
o en una seccion de directorio de usurio:

```
$HTTP["host"] =~ "api.local$" {
        server.document-root = "/home/general/Devel/receiptsapi/public/"
        accesslog.filename = "/var/log/lighttpd/receiptsapi.log"
        alias.url = ()
        url.redirect = ()
        url.rewrite-once = (
                "^/(css|img|js|fonts)/.*\.(jpg|jpeg|gif|png|swf|avi|mpg|mpeg|mp3|flv|ico|css|js|woff|ttf)$" => "$0",
                "^/(favicon\.ico|robots\.txt|sitemap\.xml)$" => "$0",
                "^/[^\?]*(\?.*)?$" => "index.php/$1"
        )
}
```

* apache2: esta es la mejor opcion, no por popular, sino por ser mas 
flexible en opciones para el novato, es la peor par produccion:

```
<VirtualHost *:80>
        ServerName api.local
        DocumentRoot /home/general/Devel/receiptsapi/public

        <Directory "/home/general/Devel/receiptsapi/public">
                DirectoryIndex index.php
                Options FollowSymLinks Indexes
                AllowOverride All
                Order deny,allow
                allow from All
        </Directory>
</VirtualHost>
```

* nginx: la conffiguracion debe secuestrar un puerto entero, asi que 
no es la mejor opcion para servidor:

```
server {
    listen 80;
    server_name api.local;
    root /home/general/Devel/receiptsapi/public;
    index index.php;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass unix:/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

