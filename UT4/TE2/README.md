# UT4-TE2: Administración de servidores web

**Índice**

**FRAMEWORK**
1. [Laravel](#1)
2. [Express](#2)
3. [Spring](#3)
4. [Ruby on Rails](#4)
5. [Django](#5)

### LARAVEL<a name="1"></a>

<div align="center">
  <img src="./Imagenes/logo_laravel.png" width="500px">
</div>

#### Instalación


Instalamos Composer como gestor de dependencias para PHP. Para ello ello hacemos lo siguiente:

```
curl -fsSL https://raw.githubusercontent.com/composer/getcomposer.org/main/web/installer \
| php -- --quiet | sudo mv composer.phar /usr/local/bin/composer
```
<div align="center">
  <img src="./Screenshots/Laravel/Captura1.png">
</div>

Comprobamos la versión instalada. En nustro caso la `2.5.1`.

```
composer --version
```
<div align="center">
  <img src="./Screenshots/Laravel/Captura2.png">
</div>

Necesitamos algunos módulos PHP, por ello actualizamos los repositorios e instalamos los siguientes paquetes de soporte:

```
sudo apt update
```
<div align="center">
  <img src="./Screenshots/Laravel/Captura3.png">
</div>


```
sudo apt install -y php8.2-mbstring php8.2-xml \
php8.2-bcmath php8.2-curl php8.2-pgsql
```
<div align="center">
  <img src="./Screenshots/Laravel/Captura4.png">
</div>

#### Crear aplicación
Creamos el directorio Laravel que será donde crearemos la estructura de la aplicación. Y nos posicionamos en dicho directorio: 

<div align="center">
  <img src="./Screenshots/Laravel/Captura5.png">
</div>

Crear estructura de la aplicación utilizando `composer` indicando el paquete `laravel/laravel` y el nombre de la aplicación `travelroad`. 

```
composer create-project laravel/laravel travelroad
```

<div align="center">
  <img src="./Screenshots/Laravel/Captura6.png">
</div>

Nos posicionamos en el directorio travelroad creado y vemos su composición.

<div align="center">
  <img src="./Screenshots/Laravel/Captura7.png">
</div>

Comprobamos que se ha instalado correctamente `artisan` preguntando por su versión:

```
./artisan --version
```
<div align="center">
  <img src="./Screenshots/Laravel/Captura8.png">
</div>

Modificamos el archivo de configuración `.env` que se crea por defecto con los datos de nuestra base de datos, en mi caso:

```
...
APP_NAME=TravelRoad
APP_ENV=development
...
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=travelroad
DB_USERNAME=travelroad_user
DB_PASSWORD=********
...
```
<div align="center">
  <img src="./Screenshots/Laravel/Captura9.png">
</div>

Continuamos configurando nginx. Para ello comenzaremos asignando los persmisos necesarios para que se pueda trabajar sin problemas de acceso.

``` sudo chgrp -R nginx storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```
<div align="center">
  <img src="./Screenshots/Laravel/Captura10.png">
</div>

Configuramos el archivo de configuración `travelroad.conf` que se encontrará en `/etc/nginx/conf.d/` con lo siguiente:

```
server {
    server_name travelroad;
    root /home/grace/Laravel/travelroad/public;

    index index.html index.htm index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

<div align="center">
  <img src="./Screenshots/Laravel/Captura11.png">
</div>

Comprobamos que la sintaxis del fichero de configuración es correcta con:

```
sudo nginx -t
``` 
<div align="center">
  <img src="./Screenshots/Laravel/Captura12.png">
</div>

Como vemos que no hay fallos recargamos la configuración de nginx:

```
sudo systemctl reload nginx
```
<div align="center">
  <img src="./Screenshots/Laravel/Captura13.png">
</div>

Añadimos el servidor `travelroad` al fichero `/etc/hosts`

<div align="center">
  <img src="./Screenshots/Laravel/Captura14.png">
</div>

Accedemos a `http://travelroad` para comprobar que funciona correctamente:

<div align="center">
  <img src="./Screenshots/Laravel/Captura15.png">
</div>

Para poder cargar los datos que deseamos y mostrarlos debemos acceder al archivo `web.php` generado en `routes/`:

```
vi /routes/web.php
```

<div align="center">
  <img src="./Screenshots/Laravel/Captura16.png">
</div>

Lo modificamos para hacer las consultas que necesitaremos:

```
<?php

use Illuminate\Support\Facades\DB;

Route::get('/', function () {
  return view('travelroad');  
});

Route::get('/visited', function () {
  $visited = DB::select('select * from places where visited = true');

return view('visited',['visited' => $visited]);  
});

Route::get('/wished', function () {
  $wished = DB::select('select * from places where visited = false');

return view('wished',['wished' => $wished]);  
});
```
<div align="center">
  <img src="./Screenshots/Laravel/Captura17.png">
</div>

A continuación creamos la plantilla `travelroad.blade.php` ubicada en `/resources/views/` que hace uso de las consultas anteriores:

<div align="center">
  <img src="./Screenshots/Laravel/Captura18.png">
</div>

Luego creamos la plantilla `wished.blade.php` y `wished.blade.php` también ubicadas en `/resources/views/`: 

**visited.blade.php**
<div align="center">
  <img src="./Screenshots/Laravel/Captura19.png">
</div> 

**wished.blade.php**
<div align="center">
  <img src="./Screenshots/Laravel/Captura20.png">
</div>

#### Produccion
Cargamos los [archivos](./src/Laravel/travelroad/) relativos a la aplicación en github.


##### Script de despliegue

Creamos el script de despliegue [deploy.sh](./src/Laravel/deploy.sh)

```
ssh arkania "cd /home/grace/DPL/dpl22-23/UT4/TE2/src/Laravel/travelroad; git pull; composer install"
```

<div align="center">
  <img src="./Screenshots/Laravel/Captura21.png">
</div>

Le damos permisos de ejecución a dicho script.

```
chmod +x deploy.sh
```

<div align="center">
  <img src="./Screenshots/Laravel/Captura22.png">
</div>

Ejecutamos el script:

```
./deploy.sh
```

<div align="center">
  <img src="./Screenshots/Laravel/Captura23.png">
</div>

##### Certificado de Seguridad
Nos trasladamos al servidor virtual para crear los certificados de seguridad. Para ello creamos el archivo de configuración `laravel.travelroad.conf`

<div align="center">
  <img src="./Screenshots/Laravel/Captura24.png">
</div>


##### URL a la aplicación


### EXPRESS<a name="2"></a>

<div align="center">
  <img src="./Imagenes/logo_express.png" width="500px">
</div>

#### Instalación


Debemos comenzar por instalar Node.js. Para ello ello hacemos lo siguiente:

```
curl -fsSL https://deb.nodesource.com/setup_current.x | sudo -E bash
```
<div align="center">
  <img src="./Screenshots/Express/Captura1.png">
</div>

No es necesario actualizar repositorios porque lo hace por nosotros. Por tanto procedemos con la instalación de Node.js:

```
sudo apt install -y nodejs
```
<div align="center">
  <img src="./Screenshots/Express/Captura2.png">
</div>

Comprobamos que se ha instalado correctamente y también comprobamos que se haya instalado `npm`

```
node --version
```
```
npm --version
```

<div align="center">
  <img src="./Screenshots/Express/Captura3.png">
</div>

#### Crear aplicación

Creamos el directorio Express que será donde crearemos la estructura de la aplicación. Y nos posicionamos en dicho directorio: 

<div align="center">
  <img src="./Screenshots/Express/Captura4.png">
</div>

Creamos la estructura de la aplicación Express utilizando `express-generator`. 

```
sudo npm install -g express-generator
```

<div align="center">
  <img src="./Screenshots/Express/Captura5.png">
</div>

Creamos la estructura de la aplicación indicándole que se utilizará `pug` como motor para las vistas. 

```
express --view=pug travelroad
```

<div align="center">
  <img src="./Screenshots/Express/Captura6.png">
</div>

Nos posicionamos en el directorio travelroad creado y vemos su composición.

<div align="center">
  <img src="./Screenshots/Express/Captura7.png">
</div>

Tal y como nos indica la salida del comando `express --view=pug travelroad` debemos instalar las dependencias:

```
npm install
```

<div align="center">
  <img src="./Screenshots/Express/Captura8.png">
</div>

Ahora probamos la aplicación:

```
DEBUG=travelroad:* npm start
```
<div align="center">
  <img src="./Screenshots/Express/Captura9.png">
</div>

Como vemos a continuación está funcionando:

<div align="center">
  <img src="./Screenshots/Express/Captura10.png">
</div>

Para poder acceder a la base de datos PosgreSQL necesitamos tener instalado node-postgre, por tanto procedemos a su instalación:

```
npm install pg
``` 

<div align="center">
  <img src="./Screenshots/Express/Captura11.png">
</div>

Guardaremos credenciales en un fichero `.env` por lo que necesitamos también el paquete dotenv, el cual instalamos a continuación:

```
npm install dotenv
``` 
<div align="center">
  <img src="./Screenshots/Express/Captura12.png">
</div>

Añadimos lo siguiente al archivo `.env`:

```
'PSQL_CONNECTION=postgresql://travelroad_user:*********@localhost:5432/travelroad'
```

> Nota: deben cambiarse los ***** por la contraseña de la base de datos. 

<div align="center">
  <img src="./Screenshots/Express/Captura13.png">
</div>

###### Lógica de negocio

Creamos el directorio `config`.

<div align="center">
  <img src="./Screenshots/Express/Captura14.png">
</div>

Dentro del directorio anterior creamos el fichero `database.js` con el siguiente contenido:

```
const { Pool } = require("pg");
require("dotenv").config();
const connectionString = process.env.PSQL_CONNECTION;
const pool = new Pool({
  connectionString,
});

module.exports = {
  query: (text, params) => pool.query(text, params),
};
```

<div align="center">
  <img src="./Screenshots/Express/Captura15.png">
</div>

Gestionamos las rutas editando el archivo `/routes/index.js`:


<div align="center">
  <img src="./Screenshots/Express/Captura16.png">
</div>

Modificamos el archivo `/views/index.pug` y creamos en el mismo directorio `visited.pug` y `wished.pug`

**index.pug**

```
extends layout                                                              
 
block content
  h1 My Travel Bucket List

  a(href='wished') Places I'd Like to Visit
  br
  a(href='visited') Places I've Already Been To
 
  p  ✨ Powered by Express
```
<div align="center">
  <img src="./Screenshots/Express/Captura17.png">
</div>

**visited.pug**

```
extends layout                                                              
   
block content
  h1 Places I've Already Been To

  ul
    each place in visited
      li= place.name
  br
  a(href='/') <- Back home
```

<div align="center">
  <img src="./Screenshots/Express/Captura18.png">
</div>

**wished.pug**

```
extends layout                                                              
   
block content
  h1 Places I'd Like to Visit

  ul
    each place in wished
      li= place.name
  br
  a(href='/') <- Back home
```
<div align="center">
  <img src="./Screenshots/Express/Captura19.png">
</div>

El resultado será el siguiente:

**index.pug**
<div align="center">
  <img src="./Screenshots/Express/Captura20.png">
</div>

**visited.pug**
<div align="center">
  <img src="./Screenshots/Express/Captura21.png">
</div>

**wished.pug**
<div align="center">
  <img src="./Screenshots/Express/Captura22.png">
</div>

##### Gestión de procesos 

Instalamos el paquete `pm2` para gestionar procesos

```
sudo npm install -g pm2
```
<div align="center">
  <img src="./Screenshots/Express/Captura23.png">
</div>

Ahora haciendo uso de dicho paquete podemos iniciar el proceso en segundo plano

```
pm2 start ./bin/www --name travelroad
```
<div align="center">
  <img src="./Screenshots/Express/Captura24.png">
</div>


##### Configuración de Nginx

Creamos el archivo de configuración para `express.travelroad.conf` en `/etc/nginx/conf.d/`

```
server { 
    server_name express.travelroad; 
  
    location / { 
        proxy_pass http://localhost:3000; 
    }  
} 
```
<div align="center">
  <img src="./Screenshots/Express/Captura25.png">
</div>

Recargamos la configuración de Nginx:

```
sudo systemctl reload nginx
```
<div align="center">
  <img src="./Screenshots/Express/Captura26.png">
</div>

Añadimos el servidor `express.travelroad` al fichero `/etc/hosts`

<div align="center">
  <img src="./Screenshots/Express/Captura27.png">
</div>

Así se vería en `express.travelroad`

<div align="center">
  <img src="./Screenshots/Express/Captura28.png">
</div>

Así se vería en `express.travelroad/visited`

<div align="center">
  <img src="./Screenshots/Express/Captura29.png">
</div>

Así se vería en `express.travelroad/wished`

<div align="center">
  <img src="./Screenshots/Express/Captura30.png">
</div>


##### Script de despliegue

Creamos el script de despliegue [deploy.sh](./src/Express/deploy.sh)

```
ssh arkania "cd /home/grace/DPL/dpl22-23/UT4/TE2/src/Express/travelroad; git pull; pm2 restart travelroad --update-env"
```

<div align="center">
  <img src="./Screenshots/Express/Captura31.png">
</div>

Le damos permisos de ejecución a dicho script.

```
chmod +x deploy.sh
```

<div align="center">
  <img src="./Screenshots/Express/Captura32.png">
</div>













##### Certificado de Seguridad




### SPRING<a name="3"></a>

<div align="center">
  <img src="./Imagenes/logo_spring.png" width="500px">
</div>

### RUBY ON RAILS<a name="4"></a>

<div align="center">
  <img src="./Imagenes/logo_ror.png" width="500px">
</div>


### DJANGO<a name="3"></a>

<div align="center">
  <img src="./Imagenes/logo_django.png" width="500px">
</div>
