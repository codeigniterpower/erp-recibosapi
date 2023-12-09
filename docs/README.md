# receiptsapi

Api para repositorio de recibos y documenos contables

La idea es un sistema que se encarge de almacenar el documento, y ofrecerlo, 
separado de todo sistema contable o de egresos o ingresos, el documento se 
le define el tipo de naturaleza si es una nota o una factura, lo que permite 
que un ingreso o egreso se le relacione los documentos pertinentes independientemente.

## Contenido

* [Introduccion](#introduccion)
* [Como usar este proyecto](#como-usar-este-proyecto)
* [Despliegue y desarrollo](#despliegue-y-desarrollo)
* [Infraestructura del proyecto](#infraestructura-del-proyecto)

## Introduccion

Este sistema es parte de otros sistemas mas grandes: [recibos](README-artifacts.md#artefactos)

Los casos de usos definen la funcionalidad del sistema aqui: [casos de uso](README-artifacts.md#casos-de-uso)

## Como usar este proyecto

Este repositorio concentra y sincroniza empleando **git-modules**,
y utilia el flujo de trabajo de ramas simplificado **GitFlow simplified**, 
favor refierase a la seccion [Desarrollo](#despliegue-y-desarrollo) donde se detalla:

```
rm -rf $HOME/Devel/receiptsapi && mkdir $HOME/Devel

git clone --recursive https://codeberg.org/codeigniter/erp-recibosapi $HOME/Devel/receiptsapi

cd $HOME/Devel &&  git submodule foreach git checkout develop && git submodule foreach git pull
```

## Despliegue y desarrollo

El api solo hara request y respons para subir archivos o pedir archivos.

En una segunda fase el sistema implementa un sistema de cola para 
notificar estas acciones.

#### Requisitos

* Linux:
  * Debian 7+ o Alpine 3.12+ unicamente
  * git 2.0+
  * php 7+ o 8+
* database
  * perconadb 5.7+ (no se recomienda mariadb, pero sirve o tambien mysql 5.7) + myrocksdb

#### Despliegue

El servicio levantara en `localhost/receiptsapi`, sin configuraciones al no ser 
una plataforma "domain driven", pero las implementaciones a estas 
deberan trabajar con CORS, por lo que el despliege para desarrollo 
sera con vesel remoto o en su defecto simple pull en una copia git `git pull origin develop`

#### Desarrollo

Con **GitFlow simplificado**, esta una rama de desarrollo `develop` predeterminada, 
y se realiza como la rama "principal" de inmediato (aqui `develop`).

La rama de preproducción es `main`, ambas ramas son protegidas 
nadie puede hacer merge sin revision, y todos los merge son squases, 
evitando mensajes vanales.

Cada merge debe cerrar uno o mas issue tipo Meta/Casos, ya que el proyecto 
se basa en alcances. Este tipo de flujo se emplea en equipos pequeños sin 
necesidad de conocimientos git, ya que ellos trabajan juntos en una "rama trabajo" 
por cada tarea asignada. Esto no sirve para grupos grandes.

https://medium.com/goodtogoat/simplified-git-flow-5dc37ba76ea8

El desarrollador clona el repositorio y crea una rama, esta 
la empieza a trabajar pero cada dia debe realizar un `git pull origin develop` 
hacia la rama trabajada.

#### Casos de uso

Deben de cumplirse todos en cada rama de etapa de proyecto, use el dashboard de los issues!

Estos describen que se quiere y como deben cumplirse

#### Estructura del repositorio

TODO

- [ ] authentication system
- [ ] user roles and crud for admins
- [ ] debug error handler
- [ ] api documentation system
- [ ] proyect documentation system

#### Convenciones de codigo

El proyecto contiene una interfaz no usable, pero que encaminan 
dos ejemplos de como construir el api:

- [ ] public upload example (Usar esto como ejemplo)
- [ ] local upload example : (usar esto como ejemplo)

Cada commit debe especificar que archivo modifica y 
al menos en que funcionalidad se esta trabajando, de al menos 1 linea
de mas de 30 caracteres explicito.

TODO

#### pruebas de despliegue y documentacion

TODO

#### rutas definidas default api

TODO

## LICENSE

The Guachi Framework is open-source software under the MIT License, this downstream part is a reduced version for!
Este minicore conteine partes del framework Banshee bajo la misma licencia.

* (c) 2023 Dias Victor @diazvictor

El proyecto receiptsapi es open source y free software bajo la licencia GPLv3 por lo que cualquier modificacion debe ser compartida.

* (c) 2023 PICCORO Lenz MCKAY @mckaygerhard
* (c) 2023 Dias Victor @diazvictor

Las adiciones y la funcionalidad estan licenciadas tambien **CC-BY-SA-NC** Compartir igual sin derecho comercial a menos que se pida permiso esten de acuerdo ambas partes, y con atribuciones de credito.

* (c) 2023 PICCORO Lenz McKAY <mckaygerhard>
* (c) 2023 Dias Victor @diazvictor

El proyecto se ha mudado al por fin madurar https://codeberg.org/codeigniter/erp-recibosapi
