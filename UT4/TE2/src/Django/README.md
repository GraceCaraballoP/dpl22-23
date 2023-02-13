## DJANGO

<div align="center">
  <img src="../../Imagenes/logo_django.png" width="500px">
</div>

**Índice**
1. [Instalación](#1)
2. [Crear proyecto](#2)
3. [Código de aplicación](#3)
4. [URLs](#4)
5. [Probando la aplicación en local](#5)
6. [Parametrizando la configuración](#6)
7. [Entorno de producción](#7)
8. [Script de despliegue](#8)
9. [Certificado de Seguridad](#9)


## Instalación<a name="1"></a>

### Python

Django es un framework escrito en Python, que se4 base en el patrón de modelo–vista–controlador (MVC).

Por tanto comenzaremos por instalar Python para poder trabajar en la aplicación web. Para ello puedes seguir esta [guía](https://github.com/GraceCaraballoP/Python).

### Entorno virtual

En Python se trabaja normalmente con entornos virtuales lo que permite aislar las dependencias de nuestro proyecto con respecto a otros proyectos o al sistema operativo.

Para crear el entorno virtual `travelroad` ejecutamos lo siguiente:

```
python -m venv --prompt travelroad .venv
```
<div align="center">
  <img src="../../Screenshots/Django/Captura1.png">
</div>

Y lo activamos con:

```
source .venv/bin/activate
```

<div align="center">
  <img src="../../Screenshots/Django/Captura2.png">
</div>


Los nuevos paquetes que instalemos dentro del entorno virtual se almacenarán en la carpeta `.venv`:


### Django

Para instalar Django y sus dependencias la herramienta pip (Package Installer for Python):

```
pip install django
```

<div align="center">
  <img src="../../Screenshots/Django/Captura3.png">
</div>

Podemos comprobar la versión instalada de Django con el siguiente comando:

```
python -m django --version
```

<div align="center">
  <img src="../../Screenshots/Django/Captura4.png">
</div>

## Crear proyecto<a name="2"></a>


Creamos la estructura del proyecto con `django-admin` que nos proporciona Django:

```
django-admin startproject main .
```

<div align="center">
  <img src="../../Screenshots/Django/Captura5.png">
</div>

Comprobamos el contenido de la carpeta de trabajo con un `tree`:

<div align="center">
  <img src="../../Screenshots/Django/Captura6.png">
</div>

Lanzamos el servidor de desarrollo con la herramienta `manage.py`:

```
./manage.py runserver
```

<div align="center">
  <img src="../../Screenshots/Django/Captura7.png">
</div>

Accedemos a [localhost:8000](http://127.0.0.1:8000/) como nos indica en el paso anterior al lanzar el servidor y comprobamos que está funcionando:

<div align="center">
  <img src="../../Screenshots/Django/Captura8.png">
</div>

## Código de aplicación<a name="3"></a>

Un proyecto en Django está formado por "aplicaciones". Por tanto comenzaremos por crear nuestra primer aplicación:

```
./manage.py startapp places
```

<div align="center">
  <img src="../../Screenshots/Django/Captura9.png">
</div>

Con lo anterior se debe haber creado un directorio que contiene la aplicación places con el siguiente contenido:

```
ls -l places
```

<div align="center">
  <img src="../../Screenshots/Django/Captura10.png">
</div>


Para poder activar la aplicación para que Django sepa de que existe. Debemos acceder al fichero `main/settings.py` y añadir `'places.apps.PlacesConfig',` dentro de `INSTALLED_APPS`:

```
vi main/settings.py
```
```
...
INSTALLED_APPS = [
    'django.contrib.admin',
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.messages',
    'django.contrib.staticfiles',
    # Nueva línea ↓
    'places.apps.PlacesConfig',
]
...
```
<div align="center">
  <img src="../../Screenshots/Django/Captura11.png">
</div>

### Acceso a la base de datos

Antes de acceder a la base de datos debemos instalar un paquete de soporte denominado `psycopg2` que nos ayudará a conectar Python con bases de datos PostgreSQL:

```
pip install psycopg2
```
<div align="center">
  <img src="../../Screenshots/Django/Captura12.png">
</div>

Una vez instalado `psycopg2` establecemos las credenciales de acceso a la base de datos que se encuentran en `main/settings.py`:

```
vi main/settings.py
```
Dentro de este fichero nos dirigimos al aprtado de `DATABASES` y lo dejamos de la siguiente manera:

```
DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.postgresql',
        'NAME': 'travelroad',
        'USER': 'travelroad_user',
        'PASSWORD': '*******',
        'HOST': 'localhost',
        'PORT': 5432,
    }
}
```
<div align="center">
  <img src="../../Screenshots/Django/Captura13.png">
</div>

>**Nota:** Tener en cuenta que debemos escribir los datos referentes a nuestra base de datos y ******* es el pasword de acceso de nuestro usuario.

Hacemos uso de `check` dentro de `manage.py` para comprobar que todo esté bien:

```
./manage.py check
```
<div align="center">
  <img src="../../Screenshots/Django/Captura14.png">
</div>

### Modelos

En Django existe un ORM que permite mapear clases escritas en Python con entidades relacionales de la base de datos.

Creamos nuestro modelo de lugares:

```
vi places/models.py
```
Al que le añadimos:

```
from django.db import models

# Create your models here.
class Place(models.Model):
    name = models.CharField(max_length=255)
    visited = models.BooleanField()

    class Meta:
        # ↓ necesario porque ya partimos de una tabla creada ↓
        db_table = "places"

    def __str__(self):
        return self.name
```
<div align="center">
  <img src="../../Screenshots/Django/Captura15.png">
</div>

### Vistas

Creamos la vista que gestionará las peticiones a la página principal:

```
vi places/views.py
```

con el siguiente contenido:

```
# from django.shortcuts import render
 
# Create your views here.
from django.http import HttpResponse
from django.template import loader

from .models import Place
  
def index(request):                                                         
    template = loader.get_template('places/index.html')
    context = {
    }
    return HttpResponse(template.render(context,request))

def visited(request):
    visited = Place.objects.filter(visited=True)
    template = loader.get_template('places/visited.html')
    context = {
        'visited': visited,
    }
    return HttpResponse(template.render(context, request))

def wished(request):
    wished = Place.objects.filter(visited=False)
    template = loader.get_template('places/wished.html')
    context = {
        'wished': wished,
    }
    return HttpResponse(template.render(context, request))

```
<div align="center">
  <img src="../../Screenshots/Django/Captura16.png">
</div>


### Plantillas

Después de las vistas creamos las plantillas.Para ello creamos los siguientes directorios:

```
mkdir -p places/templates/places
```

#### index.html
Creamos el archivo `index.html`

```
vi places/templates/places/index.html
```

Con lo siguiente:

```
<h1>My Travel Bucket List</h1>

<a href="wished">Places I'd Like to Visit</a>
<br/>
<a href="visited">Places I've Already Been To</a>

<p>✨ Powered by <strong>Express</strong></p> 
```

<div align="center">
  <img src="../../Screenshots/Django/Captura17.png">
</div>

#### visited.html

```
vi places/templates/places/visited.html
```

Con lo siguiente:

```
<h1>Places I've Already Been To</h1>

<ul>
  {% for place in visited %}
    <li>{{ place }}</li>
  {% endfor %}
</ul>
 
<a href="./"><- Back Home</a>
```
<div align="center">
  <img src="../../Screenshots/Django/Captura18.png">
</div>

#### wished.html

```
vi places/templates/places/wished.html
```

Con lo siguiente:

```
<h1>Places I'd Like to Visit</h1>

<ul>
  {% for place in wished %}
    <li>{{ place }}</li>
  {% endfor %}
</ul>

<a href="./"><- Back Home</a>
```

<div align="center">
  <img src="../../Screenshots/Django/Captura19.png">
</div>

## URLs<a name="4"></a>

Ahora debemos vincular las URLs con su respectiva vista.

Comenzamos creando el archivo de URLs para `places`:

```
vi places/urls.py
```

Con lo siguiente:

```
from django.urls import path

from . import views

app_name = 'places'

urlpatterns = [
  path('', views.index, name='index'),
  path('visited', views.visited, name='visited'),
  path('wished', views.wished, name='wished'),
]
```

<div align="center">
  <img src="../../Screenshots/Django/Captura20.png">
</div>

A continuación vinculamos estas URLs desde el fichero principal:

```
vi main/urls.py
```
Con el siguiente contenido

```
from django.contrib import admin
from django.urls import path    
# NUEVA LÍNEA ↓
from django.urls import include, path

urlpatterns = [
    path('admin/', admin.site.urls),
    # NUEVA LÍNEA ↓
    path('', include('places.urls', 'places')),
]
```

<div align="center">
  <img src="../../Screenshots/Django/Captura21.png">
</div>

## Probando la aplicación en local<a name="5"></a>

Para comprobar sque funciona correctamente solo debemos echar a correr el servidor:

```
./manage.py runserver
```

Como nos indica accedemos a http://localhost:8000 y vemos que está funcionando:

**index**

<div align="center">
  <img src="../../Screenshots/Django/Captura22.png">
</div>

**visited**

<div align="center">
  <img src="../../Screenshots/Django/Captura23.png">
</div>

**wished**

<div align="center">
  <img src="../../Screenshots/Django/Captura24.png">
</div>

<hr>

## Parametrizando la configuración<a name="6"></a>

Queremos que las credenciales a la base de datos sea un elemento configurable en función del entorno en el que estemos trabajando.

Para que las credenciales de la base de datos se puedan configurar dependiendo del entorno en el que trabajemos  usaremos un paquete de Python llamado prettyconf que sirve para cargar variables de entorno mediate un fichero de configuración.

Comenzamos instalando dicho paquete:

```
pip install prettyconf
```
<div align="center">
  <img src="../../Screenshots/Django/Captura25.png">
</div>

Modificamos el fichero `settings.py` que se encuentra en `main` para aprovechar las funcionalidades del paquete:

```
vi main/settings.py
```

Con lo siguiente:

```
...
from pathlib import Path
# ↓ Nueva línea
from prettyconf import config
# ↑ Nueva línea
...
DEBUG = config('DEBUG', default=True, cast=config.boolean)
ALLOWED_HOSTS = config('ALLOWED_HOSTS', default=[], cast=config.list)
...
DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.postgresql',
        'NAME': config('DB_NAME', default='travelroad'),
        'USER': config('DB_USERNAME', default='travelroad_user'),
        'PASSWORD': config('DB_PASSWORD', default='******'),
        'HOST': config('DB_HOST', default='localhost'),
        'PORT': config('DB_PORT', default=5432, cast=int)
    }
}
...
```
>**Nota:** Como siempre con ****** nos referimos a la contrasela de nuestro usuario de la base de datos.

<div align="center">
  <img src="../../Screenshots/Django/Captura26.png">
</div>

<div align="center">
  <img src="../../Screenshots/Django/Captura27.png">
</div>

<div align="center">
  <img src="../../Screenshots/Django/Captura28.png">
</div>

Comprobamos que todo está correcto con:

```
./manage.py check
```

<div align="center">
  <img src="../../Screenshots/Django/Captura29.png">
</div>

### Especificación de requerimientos

Debemos especificar en un fichero los requerimientos o dependencias para poder clonarlo en cualquier entorno virtual. Para ello creamos el fichero `requirements.txt` en el cual especificamos los paquetes que hemos ultilizado:

```
django
psycopg2
prettyconf
```

<div align="center">
  <img src="../../Screenshots/Django/Captura30.png">
</div>



## Entorno de producción<a name="7"></a>

Una vez subidos los archivos al control de versiones, desde arkania hacemos un `git pull` para traernos los cambios. Nos posicionamos en `Django/travelroad` y creamos el entorno virtual como anteriormente:

```
python -m venv --prompt travelroad .venv
```

<div align="center">
  <img src="../../Screenshots/Django/Captura31.png">
</div>

Accedemos al entorno virtual:

```
source .venv/bin/activate
```

<div align="center">
  <img src="../../Screenshots/Django/Captura32.png">
</div>

Instalamos los `requirements` detallados anteriormente

```
pip install -r requirements.txt
```
<div align="center">
  <img src="../../Screenshots/Django/Captura33.png">
</div>

### Parámetros para el entorno de producción

Creamos el fichero .env que contendrá las configuraciones para el entorno de producción:

```
vi .env
```

Con el siguiente contenido:

```
DEBUG=0
# ↓ Dominio en el que se va a desplegar la aplicación
ALLOWED_HOSTS=django.travelroad.alu7273.arkania.es
#DB_PASSWORD='*******'
```
<div align="center">
  <img src="../../Screenshots/Django/Captura34.png">
</div>


### Servidor de aplicación

Para el despliegue de la aplicación usaremos `gunicorn` como servidor WSGI (Web Server Gateway Interface) para Python.Para instalarlo haremos:

```
pip install gunicorn
```
<div align="center">
  <img src="../../Screenshots/Django/Captura35.png">
</div>

Lanzamos el script de gestión que permite lanzar el servidor:

```
gunicorn main.wsgi:application
```

<div align="center">
  <img src="../../Screenshots/Django/Captura36.png">
</div>

### Supervisor

Para poder mantener el servidor WSGI activo y con la posibilidad de gestionarlo (arrancar, parar, etc.) vamos a usar Supervisor que es un sistema cliente/servidor que permite monitorizar y controlar procesos en sistemas Linux/UNIX. Para instalarlo ejecutamos:

```
sudo apt install -y supervisor
```

<div align="center">
  <img src="../../Screenshots/Django/Captura37.png">
</div>

Comprobamos que el servicio está funcionando:

```
sudo systemctl status supervisor
```

<div align="center">
  <img src="../../Screenshots/Django/Captura38.png">
</div>


Supervisor viene con la herramienta supervisorctl, pero como usuarios ordinarios no tenemos permiso para poder hacer uso de ella como vemos a continuación:

```
supervisorctl status
```

<div align="center">
  <img src="../../Screenshots/Django/Captura39.png">
</div>

Para poder hacer uso de la herramienta anterior comenzaremos por añadir un grupo supervisor con permisos para ello:

```
sudo groupadd supervisor
```

<div align="center">
  <img src="../../Screenshots/Django/Captura40.png">
</div>

Editamos la configuración de Supervisor para añadir a nuestro usuario:

```
sudo vi /etc/supervisor/supervisord.conf
```

Cambiamos el chmod y seguidamente añadimos el chown:

```
...
chmod=0770               ; socket file mode (default 0700)
chown=grace:supervisor    ; grupo 'supervisor' para usuarios no privilegiados
...
```

<div align="center">
  <img src="../../Screenshots/Django/Captura41.png">
</div>

Reiniciamos el servicio de supervisor para que los cambios se hagan efectivos:

```
sudo systemctl restart supervisor
```

<div align="center">
  <img src="../../Screenshots/Django/Captura42.png">
</div>

Añadimos nuestro usuario al grupo:

```
sudo addgroup grace supervisor
```

<div align="center">
  <img src="../../Screenshots/Django/Captura43.png">
</div>

>**Nota:** Para que el cambio de grupo sea efectivo debemos cerrar la sesión y luego volver a entrar.


Comprobamos que ya no se producen errores al lanzar el controlador de supervisor:

```
supervisorctl help
```

<div align="center">
  <img src="../../Screenshots/Django/Captura44.png">
</div>


### Script de servicio

Creamos un script de servicio para nuestra aplicación que se encargue de levantar gunicorn:

```
vi run.sh
```

```
#!/bin/bash

cd /home/grace/DPL/Repo/dpl22-23/UT4/TE2/src/Django/travelroad
source .venv/bin/activate
gunicorn -b unix:/tmp/travelroad.sock main.wsgi:application

```

<div align="center">
  <img src="../../Screenshots/Django/Captura45.png">
</div>

Le damos permisos de ejecución:

```
chmod +x run.sh
```

<div align="center">
  <img src="../../Screenshots/Django/Captura46.png">
</div>


### Configuración Supervisor

Creamos la configuración de un proceso supervisor que lance nuestro script:

```
sudo vi /etc/supervisor/conf.d/travelroad.conf
```

Con lo siguiente:

```
[program:travelroad]
user = grace
command = /home/grace/DPL/Repo/dpl22-23/UT4/TE2/src/Django/travelroad/run.sh
autostart = true
autorestart = true
stopsignal = INT
killasgroup = true
stderr_logfile = /var/log/supervisor/travelroad.err.log
stdout_logfile = /var/log/supervisor/travelroad.out.log
```

<div align="center">
  <img src="../../Screenshots/Django/Captura47.png">
</div>

Ahora ya podemos añadir este proceso:

```
supervisorctl reread
supervisorctl add travelroad
supervisorctl status
```

<div align="center">
  <img src="../../Screenshots/Django/Captura48.png">
</div>


### Nginx

Finalmente configuramos el virtual host `django.travelroad.alu7273.arkania.es`:

```
sudo vi /etc/nginx/conf.d/travelroad.conf
```

Con lo siguiente:

```
server {
  server_name django.travelroad.alu7273.arkania.es;

  location / {
    include proxy_params;
    proxy_pass http://unix:/tmp/travelroad.sock;  # socket UNIX
  }
}
```
 
<div align="center">
  <img src="../../Screenshots/Django/Captura49.png">
</div>


Recargamos la configuración de Nginx para que los cambios surtan efecto:

```
sudo systemctl reload nginx
```

<div align="center">
  <img src="../../Screenshots/Django/Captura50.png">
</div>

### Aplicación en producción

Comprobamos que ya podemos acceder http://django.travelroad.alu7273.arkania.es

<div align="center">
  <img src="../../Screenshots/Django/Captura51.png">
</div>

http://django.travelroad.alu7273.arkania.es/visited

<div align="center">
  <img src="../../Screenshots/Django/Captura52.png">
</div>

Comprobamos que funciona correctamente en http://django.travelroad.alu7273.arkania.es/wished

<div align="center">
  <img src="../../Screenshots/Django/Captura53.png">
</div>


## Script de despliegue<a name="8"></a>

Creamos el script de despliegue [deploy.sh](./deploy.sh)

```
#!/bin/bash

ssh arkania "cd /home/grace/DPL/dpl22-23/UT4/TE2/src/Django/travelroad; git pull; source .venv/bin/activate; pip install -r requirements.txt; supervisorctl restart travelroad"
```

<div align="center">
  <img src="../../Screenshots/Django/Captura54.png">
</div>

Le damos permisos de ejecución a dicho script.

```
chmod +x deploy.sh
```

<div align="center">
  <img src="../../Screenshots/Django/Captura55.png">
</div>

## Certificado de Seguridad<a name="9"></a>

Finalmente lanzo certbot para crear el certificado de seguridad para `django.travelroad.alu7273.arkania.es`:

```
sudo certbot --nginx -d django.travelroad.alu7273.arkania.es
```
<div align="center">
  <img src="../../Screenshots/Django/Captura56.png">
</div>

Comprobamos que funcionan correctamente con el certificado de seguridad para [https://django.travelroad.alu7273.arkania.es](https://django.travelroad.alu7273.arkania.es)

<div align="center">
  <img src="../../Screenshots/Django/Captura57.png">
</div>

Comprobamos que funcionan correctamente con el certificado de seguridad para [https://django.travelroad.alu7273.arkania.es/visited](https://django.travelroad.alu7273.arkania.es/visited)

<div align="center">
  <img src="../../Screenshots/Django/Captura58.png">
</div>

Y finalmente para [https://django.travelroad.alu7273.arkania.es/wished](https://django.travelroad.alu7273.arkania.es/wished)

<div align="center">
  <img src="../../Screenshots/Django/Captura59.png">
</div>