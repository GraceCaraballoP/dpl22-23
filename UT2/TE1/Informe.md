# UT2-TE1: Calculadora e instalación y configuración de Nginx, Docker y PHP.

**Índice**
1. [Instalación y configuración básica de NGINX en Linux](#1)
2. [Instalación de Docker en Linux](#2)
3. [Instalación dockerizada](#3)
4. [Instalación de PHP nativo](#4)
5. [Calculadora](#5)


# Instalación y configuración básica de NGINX en Linux<a name="1"></a>

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/nginxLogo.png" width="700px">
</div>

Nginx es un servidor web/proxy inverso ligero de alto rendimiento y de código abierto.
Existen versiones para otros sistemas operativos como Windows o MacOS.

Vamos a proceder a la instalación a través de los repositorios oficiales de Nginx desde terminal.

Comenzaremos por actualizar los paquetes:

```console
grace@marte17:~$ sudo apt update
```

Luego instalamos algunos paquetes que nos serán necesarios:

```console
grace@marte17:~$ sudo apt install -y curl gnupg2 ca-certificates lsb-release debian-archive-keyring
```

A continuación descargamos la clave de firmas con curl y desarmamos y guardamos dicha clave:

```console
grace@marte17:~$ curl -fsSL https://nginx.org/keys/nginx_signing.key | sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/nginx.gpg
```

Una vez guardadas las claves añadimos las fuentes del repositorio a ***apt***:

```console
grace@marte17:~$ echo 'deb http://nginx.org/packages/debian bullseye nginx' | sudo tee /etc/apt/sources.list.d/nginx.list > /dev/null
```

Ahora volvemos a actualizar los paquetes:

```console
grace@marte17:~$ sudo apt update
```

Ahora procedemos a instalar Nginx

```console
grace@marte17:~$ sudo apt install -y nginx
Leyendo lista de paquetes... Hecho
Creando árbol de dependencias... Hecho
Leyendo la información de estado... Hecho
Los paquetes indicados a continuación se instalaron de forma automática y ya no son necesarios.
  geoip-database libgeoip1
Utilice «sudo apt autoremove» para eliminarlos.
Se instalarán los siguientes paquetes NUEVOS:
  nginx
0 actualizados, 1 nuevos se instalarán, 0 para eliminar y 50 no actualizados.
Se necesita descargar 890 kB de archivos.
Se utilizarán 3.132 kB de espacio de disco adicional después de esta operación.
Des:1 http://nginx.org/packages/debian bullseye/nginx amd64 nginx amd64 1.22.1-1~bullseye [890 kB]
Descargados 890 kB en 16s (56,9 kB/s)
Seleccionando el paquete nginx previamente no seleccionado.
(Leyendo la base de datos ... 226463 ficheros o directorios instalados actualmente.)
Preparando para desempaquetar .../nginx_1.22.1-1~bullseye_amd64.deb ...
----------------------------------------------------------------------

Thanks for using nginx!

Please find the official documentation for nginx here:
* https://nginx.org/en/docs/

Please subscribe to nginx-announce mailing list to get
the most important news about nginx:
* https://nginx.org/en/support.html

Commercial subscriptions for nginx are available on:
* https://nginx.com/products/

----------------------------------------------------------------------
Desempaquetando nginx (1.22.1-1~bullseye) ...ibb007
Configurando nginx (1.22.1-1~bullseye) ...
Created symlink /etc/systemd/system/multi-user.target.wants/nginx.service → /lib/systemd/system/nginx.service.
Procesando disparadores para man-db (2.9.4-2) ...
```

Una vez finalizada la instalación comprobamos que se ha instalado correctamente pidiendo la versión de Nginx:

```console
grace@marte17:~$ sudo nginx -V
nginx version: nginx/1.22.1
built by gcc 10.2.1 20210110 (Debian 10.2.1-6)
built with OpenSSL 1.1.1n  15 Mar 2022
TLS SNI support enabled
configure arguments: --prefix=/etc/nginx --sbin-path=/usr/sbin/nginx --modules-path=/usr/lib/nginx/modules --conf-path=/etc/nginx/nginx.conf --error-log-path=/var/log/nginx/error.log --http-log-path=/var/log/nginx/access.log --pid-path=/var/run/nginx.pid --lock-path=/var/run/nginx.lock --http-client-body-temp-path=/var/cache/nginx/client_temp --http-proxy-temp-path=/var/cache/nginx/proxy_temp --http-fastcgi-temp-path=/var/cache/nginx/fastcgi_temp --http-uwsgi-temp-path=/var/cache/nginx/uwsgi_temp --http-scgi-temp-path=/var/cache/nginx/scgi_temp --user=nginx --group=nginx --with-compat --with-file-aio --with-threads --with-http_addition_module --with-http_auth_request_module --with-http_dav_module --with-http_flv_module --with-http_gunzip_module --with-http_gzip_static_module --with-http_mp4_module --with-http_random_index_module --with-http_realip_module --with-http_secure_link_module --with-http_slice_module --with-http_ssl_module --with-http_stub_status_module --with-http_sub_module --with-http_v2_module --with-mail --with-mail_ssl_module --with-stream --with-stream_realip_module --with-stream_ssl_module --with-stream_ssl_preread_module --with-cc-opt='-g -O2 -ffile-prefix-map=/data/builder/debuild/nginx-1.22.1/debian/debuild-base/nginx-1.22.1=. -fstack-protector-strong -Wformat -Werror=format-security -Wp,-D_FORTIFY_SOURCE=2 -fPIC' --with-ld-opt='-Wl,-z,relro -Wl,-z,now -Wl,--as-needed -pie'
```

También podemos comprobar que se ha instalado correctamente preguntando por el estado del servicio:

```console
grace@marte17:~$ sudo systemctl status nginx
● nginx.service - nginx - high performance web server
 	Loaded: loaded (/lib/systemd/system/nginx.service; enabled; vendor preset: enabled)
 	Active: inactive (dead)
   	Docs: https://nginx.org/en/docs/

oct 21 15:54:29 marte17 systemd[1]: Starting A high performance web server and a reverse proxy server...
oct 21 15:54:29 marte17 systemd[1]: Started A high performance web server and a reverse proxy server.
oct 21 16:12:54 marte17 systemd[1]: Stopping A high performance web server and a reverse proxy server...
oct 21 16:12:54 marte17 systemd[1]: nginx.service: Succeeded.
oct 21 16:12:54 marte17 systemd[1]: Stopped A high performance web server and a reverse proxy server.
```
Como vemos está instalado el servicio pero está inactivo. Por lo cual procederemos a activarlo:

```console
grace@marte17:~$ sudo systemctl start nginx
```

Una vez activado volvemos a pregunar por su estado para comprobar que se haya activado correctamente.

```console
grace@marte17:~$ sudo systemctl status nginx
● nginx.service - nginx - high performance web server
     Loaded: loaded (/lib/systemd/system/nginx.service; enabled; vendor preset:>
     Active: active (running) since Mon 2022-10-24 14:35:26 WEST; 3h 7min ago
       Docs: https://nginx.org/en/docs/
    Process: 732 ExecStart=/usr/sbin/nginx -c /etc/nginx/nginx.conf (code=exite>
   Main PID: 774 (nginx)
      Tasks: 5 (limit: 4661)
     Memory: 5.3M
        CPU: 33ms
     CGroup: /system.slice/nginx.service
             ├─774 nginx: master process /usr/sbin/nginx -c /etc/nginx/nginx.co>
             ├─775 nginx: worker process
             ├─776 nginx: worker process
             ├─777 nginx: worker process
             └─778 nginx: worker process

oct 24 14:35:25 marte17 systemd[1]: Starting nginx - high performance web serve>
oct 24 14:35:26 marte17 systemd[1]: Started nginx - high performance web server.
lines 1-18/18 (END)
```

Ahora comprobamos que Nginx está funcionando correctamente accediendo a http://localhost:

```console
grace@marte17:~$ firefox localhost

```

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/localhostNginx.png" width="400px">
</div

O también podemos acceder a http://127.0.0.1:
 
```console
grace@marte17:~$ firefox 127.0.0.1

```

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/ipLocalhostNginx.png" width="400px">
</div>


# Instalación de Docker en Linux<a name="2"></a>

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/dockerLogo.png" width="800px">
</div>

Docker es una herramienta contenedora que nos sirve para la virtualización de aplicaciones.
Vamos a proceder a la instalación a través de terminal.

Comenzaremos por actualizar los paquetes:
 
 ```console
grace@marte17:~$ sudo apt update
Obj:1 http://deb.debian.org/debian bullseye InRelease
Des:2 http://deb.debian.org/debian bullseye-updates InRelease [44,1 kB]
Des:3 http://security.debian.org/debian-security bullseye-security InRelease [48,4 kB]
Des:4 http://packages.microsoft.com/repos/code stable InRelease [10,4 kB]
Des:5 http://security.debian.org/debian-security bullseye-security/main Sources [166 kB]
Des:6 http://security.debian.org/debian-security bullseye-security/main amd64 Packages [191 kB]
Des:7 http://security.debian.org/debian-security bullseye-security/main Translation-en [121 kB]
Des:8 http://packages.microsoft.com/repos/code stable/main arm64 Packages [116 kB]
Des:9 http://packages.microsoft.com/repos/code stable/main armhf Packages [116 kB]
Des:10 http://packages.microsoft.com/repos/code stable/main amd64 Packages [115 kB]
Descargados 927 kB en 2s (532 kB/s)                  	 
Leyendo lista de paquetes... Hecho
Creando árbol de dependencias... Hecho
Leyendo la información de estado... Hecho
Se pueden actualizar 45 paquetes. Ejecute «apt list --upgradable» para verlos.

```
Instalamos algunos prerequisitos:

```console
grace@marte17:~$ sudo apt install -y \
 	ca-certificates \
 	curl \
 	gnupg \
 	lsb-release
```
 
A continuación descargamos la clave de firmas con curl y desarmamos y guardamos dicha clave: 
 
```console
grace@marte17:~$ curl -fsSL https://download.docker.com/linux/debian/gpg | sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/docker.gpg
```
 
Una vez guardadas las claves añadimos las fuentes del repositorio:

```console
grace@marte17:~$ echo \ "deb [arch=$(dpkg --print-architecture)] https://download.docker.com/linux/debian \
$(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

```

Luego volvemos a actualizar los paquetes:

 ```console
grace@marte17:~$ sudo apt update
```

Seguidamente procedemos a la instalación de Docker:

 ```console
grace@marte17:~$ sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
```

Como con Nginx tenemos varias formas de comprobar que se ha realizado correctamente la instalación. Vamos a comenzar preguntando por la versión:

 ```console
grace@marte17:~$ docker --version
Docker version 20.10.20, build 9fdeb9c
```

También podemos preguntar por el servicio:

```console
sudo systemctl status docker
● docker.service - Docker Application Container Engine
     Loaded: loaded (/lib/systemd/system/docker.service; enabled; vendor preset>
     Active: active (running) since Wed 2022-10-19 20:12:25 WEST; 1min 23s ago
TriggeredBy: ● docker.socket
       Docs: https://docs.docker.com
   Main PID: 3916 (dockerd)
      Tasks: 10
     Memory: 27.1M
        CPU: 591ms
     CGroup: /system.slice/docker.service
             └─3916 /usr/bin/dockerd -H fd:// --containerd=/run/containerd/cont>

oct 19 20:12:22 marte17 dockerd[3916]: time="2022-10-19T20:12:22.916731445+01:0>
oct 19 20:12:22 marte17 dockerd[3916]: time="2022-10-19T20:12:22.916754036+01:0>
oct 19 20:12:22 marte17 dockerd[3916]: time="2022-10-19T20:12:22.916778084+01:0>
oct 19 20:12:23 marte17 dockerd[3916]: time="2022-10-19T20:12:23.540266761+01:0>
oct 19 20:12:24 marte17 dockerd[3916]: time="2022-10-19T20:12:24.408601413+01:0>
oct 19 20:12:24 marte17 dockerd[3916]: time="2022-10-19T20:12:24.639000663+01:0>
oct 19 20:12:24 marte17 dockerd[3916]: time="2022-10-19T20:12:24.930128963+01:0>
oct 19 20:12:24 marte17 dockerd[3916]: time="2022-10-19T20:12:24.930393469+01:0>
oct 19 20:12:25 marte17 systemd[1]: Started Docker Application Container Engine.
oct 19 20:12:25 marte17 dockerd[3916]: time="2022-10-19T20:12:25.091183309+01:0>
```

Debemos añadir a nuestro usuario habitual  al grupo de docker para que este usuario pueda hacer uso del servicio de Docker porque solo podrían acceder superusuarios:

 ```console
grace@marte17:~$ sudo usermod -aG docker grace
```

Usamos el contenedor que viene al instalar Docker para comprobar que funciona sin problemas:

 ```console
grace@marte17:~$ docker run hello-world
Unable to find image 'hello-world:latest' locally
latest: Pulling from library/hello-world
2db29710123e: Pull complete
Digest: sha256:18a657d0cc1c7d0678a3fbea8b7eb4918bba25968d3e1b0adebfa71caddbc346
Status: Downloaded newer image for hello-world:latest

Hello from Docker!
This message shows that your installation appears to be working correctly.

To generate this message, Docker took the following steps:
 1. The Docker client contacted the Docker daemon.
 2. The Docker daemon pulled the "hello-world" image from the Docker Hub.
    (amd64)
 3. The Docker daemon created a new container from that image which runs the
    executable that produces the output you are currently reading.
 4. The Docker daemon streamed that output to the Docker client, which sent it
    to your terminal.

To try something more ambitious, you can run an Ubuntu container with:
 $ docker run -it ubuntu bash

Share images, automate workflows, and more with a free Docker ID:
 https://hub.docker.com/

For more examples and ideas, visit:
 https://docs.docker.com/get-started/

```

Añadimos también un paquete de limpieza de docker, para ello actualizamos los paquetes:

 ```console
grace@marte17:~$ sudo apt update
```

Y lo instalamos:

```console
grace@marte17:~$ sudo apt install -y docker-clean
```


# Instalación dockerizada<a name="3"></a>

Una de las imágenes  preparadas para trabajar con Docker es Nginx. Para lanzar este contenedor debemos:

 ```console
grace@marte17:~$ docker run -p 80:80 nginx
Unable to find image 'nginx:latest' locally
latest: Pulling from library/nginx
bd159e379b3b: Pull complete
6659684f075c: Pull complete
679576c0baac: Pull complete
22ca44aeb873: Pull complete
b45acafbea93: Pull complete
bcbbe1cb4836: Pull complete
Digest: sha256:5ffb682b98b0362b66754387e86b0cd31a5cb7123e49e7f6f6617690900d20b2
Status: Downloaded newer image for nginx:latest
docker: Error response from daemon: driver failed programming external connectivity on endpoint clever_bhaskara (34add2d2592a772bba7e5bd6aab9f92781b74ffd41a69f167e1fa7bdd78e0629): Error starting userland proxy: listen tcp4 0.0.0.0:80: bind: address already in use.
ERRO[0018] error waiting for container: context canceled
```

La razón de que no haya funcionado es que ya tenemos el puerto 80 en uso por Nginx, debemos parar el servicio antes de lanzar el contenedor:

 ```console
grace@marte17:~$ sudo systemctl stop nginx
grace@marte17:~$ docker run -p 80:80 nginx
/docker-entrypoint.sh: /docker-entrypoint.d/ is not empty, will attempt to perform configuration
/docker-entrypoint.sh: Looking for shell scripts in /docker-entrypoint.d/
/docker-entrypoint.sh: Launching /docker-entrypoint.d/10-listen-on-ipv6-by-default.sh
10-listen-on-ipv6-by-default.sh: info: Getting the checksum of /etc/nginx/conf.d/default.conf
10-listen-on-ipv6-by-default.sh: info: Enabled listen on IPv6 in /etc/nginx/conf.d/default.conf
/docker-entrypoint.sh: Launching /docker-entrypoint.d/20-envsubst-on-templates.sh
/docker-entrypoint.sh: Launching /docker-entrypoint.d/30-tune-worker-processes.sh
/docker-entrypoint.sh: Configuration complete; ready for start up
2022/10/20 18:42:05 [notice] 1#1: using the "epoll" event method
2022/10/20 18:42:05 [notice] 1#1: nginx/1.23.2
2022/10/20 18:42:05 [notice] 1#1: built by gcc 10.2.1 20210110 (Debian 10.2.1-6)
2022/10/20 18:42:05 [notice] 1#1: OS: Linux 5.10.0-18-amd64
2022/10/20 18:42:05 [notice] 1#1: getrlimit(RLIMIT_NOFILE): 1048576:1048576
2022/10/20 18:42:05 [notice] 1#1: start worker processes
2022/10/20 18:42:05 [notice] 1#1: start worker process 28
2022/10/20 18:42:05 [notice] 1#1: start worker process 29
2022/10/20 18:42:05 [notice] 1#1: start worker process 30
2022/10/20 18:42:05 [notice] 1#1: start worker process 31
172.17.0.1 - - [20/Oct/2022:18:42:51 +0000] "GET / HTTP/1.1" 200 615 "-" "Mozilla/5.0 (X11; Linux x86_64; rv:102.0) Gecko/20100101 Firefox/102.0" "-"
172.17.0.1 - - [20/Oct/2022:18:42:51 +0000] "GET /favicon.ico HTTP/1.1" 404 153 "http://localhost/" "Mozilla/5.0 (X11; Linux x86_64; rv:102.0) Gecko/20100101 Firefox/102.0" "-"
2022/10/20 18:42:51 [error] 29#29: *1 open() "/usr/share/nginx/html/favicon.ico" failed (2: No such file or directory), client: 172.17.0.1, server: localhost, request: "GET /favicon.ico HTTP/1.1", host: "localhost", referrer: "http://localhost/"
2022/10/20 18:43:00 [notice] 1#1: exit
```

# Instalación de PHP nativo<a name="4"></a>

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/phpLogo.png" width="600px">
</div>

PHP es un lenguaje de programación enfocado en la programación web que permite desarrollar aplicaciones integradas en el código HTML.

Su servidor de aplicación es PHP-FPM. Acontinuación vamos a detallar los pasos para instalar PHP-FPM.

Comenzaremos por actualizar los paquetes:

```console
grace@marte17:~$ sudo apt update
```

También instalaremos algunos prerrequisitos:

```console
grace@marte17:~$ sudo apt install -y lsb-release ca-certificates \
apt-transport-https software-properties-common gnupg2
```

Una vez hecho esto añadimos las fuentes del repositorio a ***apt***:

```console
grace@marte17:~$ echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" \ | sudo tee /etc/apt/sources.list.d/sury-php.list
```

A continuación descargamos la clave de firmas con curl y desarmamos y guardamos dicha clave:

```console
grace@marte17:~$ curl -fsSL  https://packages.sury.org/php/apt.gpg \
 | sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/sury.gpg

```

Actualizamos nuevamente los repositorios:

```console
grace@marte17:~$ sudo apt update
```

A continuación buscamos las versiones disponibles que tenemos de php

```console
grace@marte17:~$ apt-cache search --names-only 'php*-fpm'
php7.4-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary)
php-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary) (default)
php5.6-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary)
php7.0-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary)
php7.1-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary)
php7.2-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary)
php7.3-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary)
php8.0-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary)
php8.1-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary)
php8.2-fpm - server-side, HTML-embedded scripting language (FPM-CGI binary)
```

Como vemos la más reciente es la 8.2 y es la que instalaremos:

```console
grace@marte17:~$ sudo apt install -y php8.2-fpm
```

Una vez finalizada la instalación comprobamos que se ha realizado correctamente preguntando por la versión

```console
grace@marte17:~$ php --version
PHP 8.2.0RC3 (cli) (built: Sep 29 2022 22:12:49) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.2.0RC3, Copyright (c) Zend Technologies
    with Zend OPcache v8.2.0RC3, Copyright (c), by Zend Technologies
```

También podríamos comprobarlo preguntando por el servicio:

```console
grace@marte17:~$ sudo systemctl status php8.2-fpm
● php8.2-fpm.service - The PHP 8.2 FastCGI Process Manager
     Loaded: loaded (/lib/systemd/system/php8.2-fpm.service; enabled; vendor pr>
     Active: active (running) since Thu 2022-10-20 19:47:47 WEST; 30s ago
       Docs: man:php-fpm8.2(8)
    Process: 11550 ExecStartPost=/usr/lib/php/php-fpm-socket-helper install /ru>
   Main PID: 11547 (php-fpm8.2)
     Status: "Processes active: 0, idle: 2, Requests: 0, slow: 0, Traffic: 0req>
      Tasks: 3 (limit: 4661)
     Memory: 9.2M
        CPU: 49ms
     CGroup: /system.slice/php8.2-fpm.service
             ├─11547 php-fpm: master process (/etc/php/8.2/fpm/php-fpm.conf)
             ├─11548 php-fpm: pool www
             └─11549 php-fpm: pool www

oct 20 19:47:47 marte17 systemd[1]: Starting The PHP 8.2 FastCGI Process Manage>
oct 20 19:47:47 marte17 systemd[1]: Started The PHP 8.2 FastCGI Process Manager.
```

Como hemos comprobado se ha instalado correctamente para comprobar que funciona haremos una prueba para cver si devuelve el mensaje:

```console
grace@marte17:~$ php -r "echo 'Hello World';"
Hello World
```


##  Habilitar PHP en Nginx

Accedemos al fichero `/etc/php/8.2/fpm/pool.d/www.conf` y lo modificamos como se ve a continuación:

```console
grace@marte17:~$ sudo vi /etc/php/8.2/fpm/pool.d/www.conf

23| user = nginx
24| group = nginx
...
51| listen.owner = nginx
52| listen.group = nginx
```

Luego guardamos los cambios y recargamos el servicio de **PHP-FPM**

```console
grace@marte17:~$ sudo systemctl reload php8.2-fpm
```

Y habilitamos la comunicación  entre Nginx y PHP-FPM accediendo a **/etc/nginx/conf.d/default.conf** y modificando el bloque de **location / ** y el de **location ~ \.php$**

```console
grace@marte17:~$ sudo vi /etc/nginx/conf.d/default.conf

   location / {
        #root   /usr/share/nginx/html;
        root	/home/grace/DPL;
	       index  index.php index.html index.htm;
    }
   
   location ~ \.php$ {
        root		/home/grace/DPL;
        fastcgi_pass	unix:/var/run/php/php8.2-fpm.sock;
        index		index.php;
        include		fastcgi_params;
        fastcgi_param	SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
```

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/default_conf.png" width="600px">
</div>

Comprobamos que la sintaxis de la configuración de Nginx sea correcta:

```console
grace@marte17:~$ sudo nginx -t
nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

Y como si lo es recargamos el servicio de Nginx

```console
grace@marte17:~$ sudo systemctl reload nginx
```


# Calculadora<a name="5"></a>

Para el ejercicio de la calculadora como debemos redirigir el **localhost** a **calculator.native** y  **calculator.docker** modifiqué la primera línea del fichero **/etc/hosts** quedando de la siguiente forma:

```console
grace@marte17:~$ cat /etc/hosts
127.0.0.1	localhost calculator.native calculator.docker
```
<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/hosts.png" width="600px">
</div>

Así podremos acceder también al llamar a localhost:

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/calculadoraLocalhost.png" width="400px">
</div>

## Nginx Dockerizado

Para el ejercicio de la calculadora con Nginx Dockerizado he creado la siguiente estructura:

```console
grace@marte17:~/DPL/Calculadora$ tree
.
├── default.conf
├── docker-compose.yml
└── src
    ├── calculadora.php
    ├── css
    │   └── calculadora.css
    └── img
        └── calculadora.png

3 directories, 5 files
```

Dónde el contenido de **default.conf** es:

```
server {
  server_name _;
  index index.php index.html;

  location ~ \.php$ {
    fastcgi_pass php-fpm:9000;
    include fastcgi_params;  # fichero incluido en la instalación
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
  }
}
```

Y el de **docker-compose.yml**

```
version: "3.3"

services:
  web:
    image: nginx
    volumes:
      - ./src:/etc/nginx/html # "root" por defecto en Nginx
      - ./default.conf:/etc/nginx/conf.d/default.conf
    ports:
      - 80:80

  php-fpm:
    image: php:8-fpm
    volumes:
      - ./src:/etc/nginx/html
```

Una vez realizados los cambios levantamos los servicios:

```console
grace@marte17:~/DPL/Calculadora$ docker compose up
```

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/calculadoraDocker.png" width="400px">
</div>

## Nginx

Volvemos a activar el servicio de Nginx y llamamos a **calculator.native** 

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/calculadoraNginx.png" width="400px">
</div>

## Resultado

En ambos casos debería de funcionar tal que así:

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/operacionConNginx.png" width="400px">
</div>

Y al pulsar en **Realizar Operación**

<div align="center">
  <img src="https://github.com/GraceCaraballoP/dpl22-23/blob/main/UT2/TE1/img/resultadoConNginx.png" width="400px">
</div>
