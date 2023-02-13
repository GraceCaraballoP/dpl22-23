## EXPRESS

<div align="center">
  <img src="../../Imagenes/logo_express.png" width="500px">
</div>

**Índice**
1. [Instalación](#1)
2. [Crear aplicación](#2)
3. [Lógica de negocio](#3)
4. [Gestión de procesos](#4)
5. [Configuración de Nginx](#5)
6. [Script de despliegue](#6)
7. [Despliegue](#7)
8. [Certificado de Seguridad](#8)



## Instalación<a name="1"></a>


Debemos comenzar por instalar Node.js. Para ello ello hacemos lo siguiente:

```
curl -fsSL https://deb.nodesource.com/setup_current.x | sudo -E bash
```
<div align="center">
  <img src="../../Screenshots/Express/Captura1.png">
</div>

No es necesario actualizar repositorios porque lo hace por nosotros. Por tanto procedemos con la instalación de Node.js:

```
sudo apt install -y nodejs
```
<div align="center">
  <img src="../../Screenshots/Express/Captura2.png">
</div>

Comprobamos que se ha instalado correctamente y también comprobamos que se haya instalado `npm`

```
node --version
```
```
npm --version
```

<div align="center">
  <img src="../../Screenshots/Express/Captura3.png">
</div>

## Crear aplicación<a name="2"></a>

Creamos el directorio Express que será donde crearemos la estructura de la aplicación. Y nos posicionamos en dicho directorio: 

<div align="center">
  <img src="../../Screenshots/Express/Captura4.png">
</div>

Creamos la estructura de la aplicación Express utilizando `express-generator`. 

```
sudo npm install -g express-generator
```

<div align="center">
  <img src="../../Screenshots/Express/Captura5.png">
</div>

Creamos la estructura de la aplicación indicándole que se utilizará `pug` como motor para las vistas. 

```
express --view=pug travelroad
```

<div align="center">
  <img src="../../Screenshots/Express/Captura6.png">
</div>

Nos posicionamos en el directorio travelroad creado y vemos su composición.

<div align="center">
  <img src="../../Screenshots/Express/Captura7.png">
</div>

Tal y como nos indica la salida del comando `express --view=pug travelroad` debemos instalar las dependencias:

```
npm install
```

<div align="center">
  <img src="../../Screenshots/Express/Captura8.png">
</div>

Ahora probamos la aplicación:

```
DEBUG=travelroad:* npm start
```
<div align="center">
  <img src="../../Screenshots/Express/Captura9.png">
</div>

Como vemos a continuación está funcionando:

<div align="center">
  <img src="../../Screenshots/Express/Captura10.png">
</div>

Para poder acceder a la base de datos PosgreSQL necesitamos tener instalado node-postgre, por tanto procedemos a su instalación:

```
npm install pg
``` 

<div align="center">
  <img src="../../Screenshots/Express/Captura11.png">
</div>

Guardaremos credenciales en un fichero `.env` por lo que necesitamos también el paquete dotenv, el cual instalamos a continuación:

```
npm install dotenv
``` 
<div align="center">
  <img src="../../Screenshots/Express/Captura12.png">
</div>

Añadimos lo siguiente al archivo `.env`:

```
PSQL_CONNECTION=postgresql://travelroad_user:*********@localhost:5432/travelroad
```

> Nota: deben cambiarse los ***** por la contraseña de la base de datos. 

<div align="center">
  <img src="../../Screenshots/Express/Captura13.png">
</div>

## Lógica de negocio<a name="3"></a>

Creamos el directorio `config`.

<div align="center">
  <img src="../../Screenshots/Express/Captura14.png">
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
  <img src="../../Screenshots/Express/Captura15.png">
</div>

Gestionamos las rutas editando el archivo `/routes/index.js`:


<div align="center">
  <img src="../../Screenshots/Express/Captura16.png">
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
  <img src="../../Screenshots/Express/Captura17.png">
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
  <img src="../../Screenshots/Express/Captura18.png">
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
  <img src="../../Screenshots/Express/Captura19.png">
</div>

El resultado será el siguiente:

**index.pug**
<div align="center">
  <img src="../../Screenshots/Express/Captura20.png">
</div>

**visited.pug**
<div align="center">
  <img src="../../Screenshots/Express/Captura21.png">
</div>

**wished.pug**
<div align="center">
  <img src="../../Screenshots/Express/Captura22.png">
</div>

## Gestión de procesos<a name="4"></a>

Instalamos el paquete `pm2` para gestionar procesos

```
sudo npm install -g pm2
```
<div align="center">
  <img src="../../Screenshots/Express/Captura23.png">
</div>

Ahora haciendo uso de dicho paquete podemos iniciar el proceso en segundo plano

```
pm2 start ../../bin/www --name travelroad
```
<div align="center">
  <img src="../../Screenshots/Express/Captura24.png">
</div>


## Configuración de Nginx<a name="5"></a>

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
  <img src="../../Screenshots/Express/Captura25.png">
</div>

Recargamos la configuración de Nginx:

```
sudo systemctl reload nginx
```
<div align="center">
  <img src="../../Screenshots/Express/Captura26.png">
</div>

Añadimos el servidor `express.travelroad` al fichero `/etc/hosts`

<div align="center">
  <img src="../../Screenshots/Express/Captura27.png">
</div>

Así se vería en `express.travelroad`

<div align="center">
  <img src="../../Screenshots/Express/Captura28.png">
</div>

Así se vería en `express.travelroad/visited`

<div align="center">
  <img src="../../Screenshots/Express/Captura29.png">
</div>

Así se vería en `express.travelroad/wished`

<div align="center">
  <img src="../../Screenshots/Express/Captura30.png">
</div>


## Script de despliegue<a name="6"></a>

Creamos el script de despliegue [deploy.sh](./deploy.sh)

```
ssh arkania "cd /home/grace/DPL/dpl22-23/UT4/TE2/src/Express/travelroad; git pull; pm2 restart travelroad --update-env"
```

<div align="center">
  <img src="../../Screenshots/Express/Captura31.png">
</div>

Le damos permisos de ejecución a dicho script.

```
chmod +x deploy.sh
```

<div align="center">
  <img src="../../Screenshots/Express/Captura32.png">
</div>

## Despliegue<a name="7"></a>

Ocultamos el archivo `.env` con un `.gitignore` y subimos los archivos al repositorio de `dpl22-23/UT4/TE2`

Nos trasladamos al servidor virtual  e instalamos nodejs como lo hicimos anteriormente en local y nos traemos los cambios del repositorio con un `git pull` en `dpl22-23/UT4`

Creamos el archivo .env en `dpl22-23/UT4/TE2/src/Express/travelroad`

```
PSQL_CONNECTION=postgresql://travelroad_user:*********@localhost:5432/travelroad
```

> Nota: deben cambiarse los ***** por la contraseña de la base de datos. 

<div align="center">
  <img src="../../Screenshots/Express/Captura33.png">
</div>

Nos dirigimos a `/etc/nginx/conf.d` y creamos el archivo de configuracion `express.travelroad.conf`

<div align="center">
  <img src="../../Screenshots/Express/Captura34.png">
</div>

Recargamos el servicio de nginx:

```
sudo systemctl reload nginx
```
<div align="center">
  <img src="../../Screenshots/Express/Captura35.png">
</div>

Comprobamos que funciona correctamente en `http://express.travelroad.alu7273.arkania.es`

<div align="center">
  <img src="../../Screenshots/Express/Captura36.png">
</div>

También en `http://express.travelroad.alu7273.arkania.es/visited`:

<div align="center">
  <img src="../../Screenshots/Express/Captura37.png">
</div>

Comprobamos que funciona correctamente en `http://express.travelroad.alu7273.arkania.es/wished`

<div align="center">
  <img src="../../Screenshots/Express/Captura38.png">
</div>

## Certificado de Seguridad<a name="8"></a>

Finalmente lanzo certbot para crear el certificado de seguridad para `express.travelroad.alu7273.arkania.es`:

<div align="center">
  <img src="../../Screenshots/Express/Captura39.png">
</div>

Comprobamos que funcionan correctamente con el certificado de seguridad para [https://express.travelroad.alu7273.arkania.es](https://express.travelroad.alu7273.arkania.es)

<div align="center">
  <img src="../../Screenshots/Express/Captura40.png">
</div>

Comprobamos que funcionan correctamente con el certificado de seguridad para [https://express.travelroad.alu7273.arkania.es/visited](https://express.travelroad.alu7273.arkania.es/visited)

<div align="center">
  <img src="../../Screenshots/Express/Captura41.png">
</div>

Y finalmente para [https://express.travelroad.alu7273.arkania.es/wished](https://express.travelroad.alu7273.arkania.es/wished)

<div align="center">
  <img src="../../Screenshots/Express/Captura42.png">
</div>