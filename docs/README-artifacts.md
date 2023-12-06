# artefactos de tecnologia

## actores

Realizan acciones para que se cumpla un proceso:

#### COMPRADOR

Es un usuario, El que va adquirir el [articulo(S)](#articulo) o **rif del agente (de retencion)**, 
es el que entrega dinero a el `VENDEDOR`:

#### VENDEDOR

Es un usuario, El que ofrece el bien a comprar o **rif del sujeto (retenido)**, 
este recibe el dinero y entrega el [articulo(S)](#articulo) al `COMPRADOR`.

#### CONTABILIDAD

ES un usuario o tambien una entidad o un sistema no usuario, 
Es o son quien(es) emite(n) y procesa(n) la informacion solo de [facturas](#factura) 
para asi procesar el ISLR o IVA, ya que debe contabilizar el [recibo de tipo factura](#factura) 
unicamente, para realizar retenciones y declaraciones al fisco.

#### GASTOS

ES un usuario o tambien una entidad o un sistema no usuario, 
Es o son quien(es) emite(n) y procesa(n) la informacion de [recibos](#recibo) 
tanto [facturas](#factura) como [notas](#notas), estos no procesan informacion, solo 
la registran para contabilizar costes e inversion.

## artefactos involucrasos

#### PRODUCTO

El el bien recibido por el **rif del agente** o `VENDEDOR` 
ofrecido por el **rif del sujeto** o `VENDEDOR`
hay de dos tipos:

###### articulos

Que puede ser computadores, escritorios, comida sin cocer, estos
**IMPORTANTE** estos generan IVA de uno o varios tipos.

###### servicios

Que puede ser prestados consumidos (comida cocida servida, internet, agua)
**IMPORTANTE** estos generan ISLR y deben ser declarados

#### RECIBO

Documento legal que acredita la recepción de productos/mercancías 
o dinero, las hay de varios tipos aqui siendo las mas importantes:

###### factura

Recibo, pero cuando tiene legalidad pero a nivel fiscal

###### notas

De entrega o de credito o de pagos, no tienen validez sino entre las partes

#### SISTEMA

Sonlso distintos artefactos de automatizacion de informacion, 
en la red y estaninvolucrados varios

* repositorio de recibos
* sistema de retenciones
* sistema de gastos
* interfaz de recibos (subidas, y administracion)

#### SERVIDOR

Es el lugar donde residen estos sistemas y por medio el cual 
los actores (de los cuales pueden ser usuarios o no) participan 
e interactuan con la informacion y los adjuntos o [recibos](#recibos) .

## proceso de repositorio recibos

El sistema es solo un API es decir ningun actor interactua 
directamente conel, pero este participa en los proceso, 
asi que se describe estos y la participacion de los actores:

1. COMPRADOR : compra con el rif, una nevera, una comida, paga el internet, como ejemplos
2. COMPRADOR : noitificar que se es contribuyente especial al vendedor
    * aeriguar que pasa si el que compra no lo dice
    * vendedor no esta en el deber de indicarle que retenga, el comprador debe hacerlo

#### Etapa 1 desarrollo

Esta etapa solo fabria el sistema de repositorio de recibos, 
y su funcionalidad se limitara a subir y mostrar recibos, 
con una interfaz embebida minima sin administracion:

###### proceso etapa 2 desarrollo

El sistema de resibos solo recive una peticion POST con el 
fomulario multiform los datos aqui y el adjunto.

* 3 - COMPRADOR : inmediatamente cargar la factura al sistema sea por telefono o por pc, y llenar los datos
    * rif del agente de retencion o el rif del que compra o el que hace la compra
    * rif del sujeto retenido o rif de la razon social a la que compran (el rif del negocio al que hicieron la compra)
    * numero de factura OJO no es lo mismo que numero de control
    * numero de control: OJO si es ticket o no tiene usar el numero de la caja, o el numero al final en la linea MH
    * fecha de la factura (no es lo mismo que fecha de entrega de factura ni fecha de realizacion de dicha compra)
    * fecha de la compra (ojo puede no ser la misma de la fecha de la factura), fecha de comra = fecha que se concreta o entrega dinero, el sistem automatico pone la del dia y el tipo la modifica hacia adelante
    * base imponible (el monto de la compra, pero solo los que se les saca iva pero sin el iva, o el monto cantidad de objeto de retencion)
    * base excento (el monto de la compra pero solo los que no se les aplica iva, total, o el monto si derecho a credito fiscal)
    * monto del iva (el iva correspondinte, ojo no simepre es 16% puede variar, es solo en bolivares asi en el pago esten dolares incluidos, se les saca convertido en bolivares, llamado impuesto iva)
    * porcentaje iva (este puede variasr, si es productos de transporte no es 16 por ejemplo)
    * monto total (es el del imponible + excento+ mas el del iva , es el total de compras)
    * **flag o selector** si es salida de dinero para pagar un **servicio** (pagos) o si es salida de dinero para **articulo** adquiridos (compra)

#### ETAPA 2 desarrollo

Esta etapa agrega un sistema de cola, para llevar control de el 
adjunto procesado, se limitara a marcar la cola en tres estados:

###### estado pendiente

Marca enla cola que el adjunto es nuevoy aun nadie sabe de el, 
segun el tipo de adjunto y naturaleza, envia notificaciones.

###### estado leido

Marca eladjunto como que ya fue obtenido del api por un sistema 
remoto (halo el adjunto por el gasto o retencion) y el usuario 
realizao algo con el mismo.

###### estado procesado

Marca que el adjunto ya esta en el otro sistema (ejemplo gasto, retencion)
y se saca de la cola.

###### proceso etapa 2 desarrollo

* 4 - SISTEMA: se coloca en la cola de la lista de procesos pendientes de retencion y avisos de gastos **todo salida de dinero genera un recibo**
    * SISTEMA: poner disponible el recibo cargado, por medio del api
    * SISTEMA: colocar en cola de el proceso, 
    * SISTEMA: enviar notificacion para gastos, siempre que se suba un recibo
    * SISTEMA: clasificarlo, entre esas clasificaciones estan la mas importante: servicio(pago) o articulo(compra), y contribuyente especial o no
    * si es contribuyente especial
        * SISTEMA: enviar notificacion para contabilidad siempre que sea un recibo tipo factura y sea contribuyente especial
        * si es un **servicio**
            * SISTEMA: colocar en otra cola solo para contabilidad, de el proceso, de retenciones para ISLR
        * si es un **articulo**
            * SISTEMA: colocar en otra cola solo para contabilidad, de el proceso, de retenciones para IVA

## Casos de uso base

Estos determinan el desarrollo, son los logros o objetivos del desarrollo 
pendientes por cumplir, los WIP son los que se estan trabajando.

Los casos de uso solo tienen la etiqueta **"Metas/Propuestas"** .

| flujo | tarea | caso de uso / actor | COMPRADOR | CONTABILIDAD | GASTOS |
| ----- | ----- | ------------------- | --------- | ---------- | -------- |
|  1.   |  #5   | iniciar sesion      |    x      |     x      |    x     |
|  2.   |  #6   |  cargar recibo      |    x      |            |          |
|  3.   |  #8   |  listar recibos     |    x      |     x      |    x     |
|  4.   |  #9   |  buscar recibos     |    x      |     x      |    x     |
|  5.   |  #7   |  detalle recibo     |    x      |     x      |    x     |
|  6.   |       |  marcar recibo      |           |     x      |    x     |
|  7.   |       |  cerrar recibo      |           |     x      |    x     |
|  8.   |       |  anular recibo      |           |            |    x     |
|  9.   |       |  borrar recibo      |           |            |    x     |

En la segunda etapa se empleara el sistema de permisos

Realizar el primer punto de entrada del api:

### Caso de Uso Iniciar sesión

Descripción El actor ( no importa cual) inicia sesión en el sistema para
poder hacer uso de sus privilegios y funciones.

- Actores Principales COMPRADOR, VENDEDOR, CONTABILIDAD, GASTOS
- Usuario Funcional
- Actor Secundario: Base de datos, API receiptsapi

##### Condiciones y flujo

* 1 el API recibe los parametros POST
    - `username` : REQUERIDO : usuario entre 6 y 20 caracters alfanumericos
    - `userpass` : REQUERIDO : contraseña entre 6 y 20 caracteres alfanumericos
    - `userkey` : REQUERIDO : palabra de 40 caracteres alfanumerica
* 2 El API valida los datos: RESPUESTA datos correctos
    - mensaje json de datos correctos y permisos de usuarios

##### Post condiciones

* 3 El sistema permite el acceso: RESPUESTA sesion iniciada
    - mensaje json de datos correctos y permisos de usuarios

##### Flujos Alternativos

* 2 El API valida los datos: RESPUESTA datos erroneos
    - mensaje json de datos incorrectos session invalida
* 2 El API valida los datos: RESPUESTA api key no esta confifurada
    - mensaje json de datos incorrectos y mensaje que la apikey no es valida
    - NOTA: no se dira que la apikey no esta configurada sino que no es valida
* 3 El sistema IMPIDE el acceso: RESPUESTA sesion incorrecta
    - mensaje json de datos incorrectos sea mensaje que la apikey no es valida o de la clave

Realizar el segundo punto de entrada del api:

### Caso de Uso cargar recibo

Descripción: El actor ( no importa cual) carga un documento.

- Actores Principales COMPRADOR
- Actor Secundario: Base de datos, API receiptsapi

##### Pre condiciones

- #5  [Caso de uso iniciar sesion](#caso-de-uso-iniciar-sesión)
  esta se verifica desde las cabeceras junto con su clave.
- Actor unico COMPRADOR
- Artefactos: recibo escaneado o fotografiado
- DIASCARGA : cantidad de dias permitido para cargar recibos

##### Condiciones y flujo

* 1 el API recibe los parametros POST
    - `rif_agente` : REQUERIDO : rif del comprador
    - `rif_sujeto` : OPCIONAL : rif del vendedor
    - `num_recibo` : OPCIONAL/REQUERIDO : si hay rif de vendedor, es requerido, es el numero de factura
    - `num_control` : OPCIONAL/REQUERIDO : si hay rif de vendedor, requerido, es numero caja o numero linea MH
    - `fecha_factura` : REQUERIDO : fecha de la factura/nota indicada en el recibo
    - `fecha_compra` : OPCIONAL : se pone a la fecha actual (de no venir) o maximo DIASCARGA atras
    - `monto_factura` : REQUERIDO : base imponible monto total toda la factura, solo positivos sin cero
    - `monto_excento` : REQUERIDO : monto de la compra que no se le aplica iva, solo positivos incluye cero
    - `tipo_recibo` : REQUERIDO : factura o nota
    - `adjunto_recibo` : REQUERIDO : el recibo de la factura o nota escaneado
* 2 El API valida los datos: PROCESA LOS DATOS
    - el api guarda en base de datos los datos y tambien el recibo en base64
    - el api tambien guarda el recibo en el sistema de archivos, tabla anual
        - la ruta en el sistema de archivos es separada directorios por años
    - mensaje json de el id del recibo YYYYMMDDHHmmss, mensaje archivo subido correctamente

##### Post condiciones

* 3 El API valida los datos: RESPUESTA detalle del recibo cargado
    - mensaje json de la clasificacion de el recibo y ruta del archivo adjuntado
    - la respuesta de [CAso de uso #7  : detalle recibo]()

##### Flujos Alternativos

* 2 El API valida los datos: RESPUESTA error en el proceso
    - mensaje json de datos correctos y el error del sistema si no puedo guardar o paso algo

* 3 El API valida los datos: RESPUESTA datos incompletos o incorrectos
    - mensaje json de datos correctos y los campos que tienen los datos incorrectos


### Caso de Uso listar recibos

Listar resumido las facturas o documentos cargados en el sistema segun criterios de filtrado o busqueda

- Actores Principales COMPRADOR, GASTOS, CONTABILIDAD
- Actor Secundario: Base de datos, API receiptsapi

##### Pre condiciones

- #5  [Caso de uso iniciar sesion](#caso-de-uso-iniciar-sesión)
  esta se verifica desde las cabeceras junto con su clave.
- DIASCARGA : cantidad de dias permitido para cargar recibos

##### Condiciones y flujo

* 1 el API recibe los parametros GET, solo llama el punto de entrada
    - `rif_agente` : OPCIONAL : rif del comprador
    - `rif_sujeto` : OPCIONAL : rif del vendedor
    - `num_recibo` : OPCIONAL : numero de factura
    - `num_control` : OPCIONAL : numero caja o numero linea MH
    - `fecha_factura` : OPCIONAL : fecha de la factura/nota indicada en el recibo
    - `fecha_compra` : OPCIONAL : se pone a la fecha actual (de no venir) o maximo DIASCARGA atras
    - `monto_factura` : OPCIONAL : total toda la factura, solo positivos sin cero
    - `tipo_recibo` : OPCIONAL : factura o nota

##### Post condiciones

* 2 El API lista paginado la primera pagina y en ella los ultimos 100 recibos segun los criterios filtrados
    - Si el actor es COMPRADOR, solo se listan sus recibos, Caso contrario se listan todos los recibos
    - mensaje json de exito segun el actor en sesion
    - listado con estas colunmas:
        - `cod_recibo` : mostrar : YYYYMMDDHHMMSS
        - `rif_agente` : mostrar
        - `rif_sujeto` : mostrar si existe sino vacio
        - `fecha_compra` : mostrar, formato YYYYMMDD
        - `monto_factura` : mostrar o 0, si cero mostrar "error" al lado

##### Flujos Alternativos

* 2 El API no muestra recibos si no los hay o no pertenecen al actor GASTOS o CONTABILIDAD
    - mensaje json con el estado en error y el mensaje
    - devuelve una linea enla primera pagina todos los mismos campos pero con valores en 0 o vacios

* 2 El API no muestra recibos si los existentes son mas de un año o no pertenecen al actor GASTOS o CONTABILIDAD
    - mensaje json con el estado en error y el mensaje que no se han realizado cargas recientes
    - devuelve una linea enla primera pagina todos los mismos campos pero con valores en 0 o vacios


Realizar el cuarto punto de entrada del api:

### Caso de Uso detalle recibo

Detalle completo de un recibo o documento en el sistema ya cargado este anulado o no, tenga mas de un año o no!

- Actores Principales COMPRADOR, GASTOS, CONTABILIDAD
- Actor Secundario: Base de datos, API receiptsapi

##### Pre condiciones

- #5  [Caso de uso iniciar sesion](#caso-de-uso-iniciar-sesión)
  esta se verifica desde las cabeceras junto con su clave.
- COMPRADOR: se mostrara el recibo si este lo cargo dicho usuario
- GASTOS/CONTABILIDAD: puede ver cualquier recibo

##### Condiciones y flujo

* 1 el API recibe los parametros GET, solo llama el punto de entrada
    - `cod_recibo` : REQUERIDO : id de factura YYYYMMDDHHMMSS

##### Post condiciones 

* 2 El API lista paginado la primera pagina y en ella EL DETALLE del recibo segun el codigo de id dado
    - Si el actor es COMPRADOR, solo se listan sus recibos, Caso contrario se listan todos los recibos
    - mensaje json de exito segun el actor en sesion
    - listado con estas colunmas:
        - `cod_recibo` : mostrar : YYYYMMDDHHMMSS
        - `rif_agente` : mostrar
        - `rif_sujeto` : mostrar si existe sino vacio
        - `num_factura` : mostrar siempre
        - `num_control` : mostrar si existe sino vacio
        - `fecha_factura` : mostrar, formato YYYYMMDD
        - `fecha_compra` : mostrar, formato YYYYMMDD
        - `monto_factura` : mostrar o 0, si cero mostrar "error" al lado
        - `monto_excento` : mostrar o 0
        - `tipo_recibo` : FACTURA/NOTA
        - `adjunto_recibo` : 
            - ruta en sistema de archivo, el api debe verificar si existe, ruta publica del server
            - caso contrario usa el guardado en la DB base64 y lo vuelve colocar en el sistema de ficheros

##### Flujos Alternativos

* 2 El API no muestra recibos porque no existe el id
    - mensaje json con el estado en error y el mensaje, EL ID RECIBO NO EXISTE EN EL SISTEMA
    - devuelve una linea enla primera pagina todos los mismos campos pero con valores en 0 o vacios

* 2 El API no muestra recibos porque el recibo no le pertenece
    - mensaje json con el estado en error y el mensaje, EL ID RECIBO NO LE PERTENECE
    - devuelve una linea enla primera pagina todos los mismos campos pero con valores en 0 o vacios

* 2 El API no muestra recibos porque el parametro es incorrecto o hay un problema en la db
    - mensaje json con el estado en error y el mensaje, ERROR INTERNO O PARAMETRO INCORRECTO
    - devuelve una linea enla primera pagina todos los mismos campos pero con valores en 0 o vacios

