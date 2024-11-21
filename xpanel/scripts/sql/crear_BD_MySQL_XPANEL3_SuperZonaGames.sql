/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     22/04/2013 07:35:05 p.m.                     */
/*==============================================================*/


drop table if exists ARCHIVOS;

drop table if exists BOLETIN;

drop table if exists CONTACTOS;

drop table if exists CONTACTOS_ACCEDEN;

drop table if exists CONTACTOS_DESCARGAN;

drop table if exists CONTACTOS_PERTENECE;

drop table if exists CONTACTOS_RECIBEN;

drop table if exists CONTADOR;

drop table if exists CONTENIDOS;

drop table if exists COTIZACIONES;

drop table if exists COTIZACION_POSEE_PRODUCTOS;

drop table if exists EGRESOS;

drop table if exists EGRESO_POSEE_PRODUCTOS;

drop table if exists FOTOGALERIA;

drop table if exists GRUPOS;

drop table if exists INVENTARIOS;

drop table if exists INVENTARIO_POSEE_PRODUCTOS;

drop table if exists MENSAJES;

drop table if exists MENU_MODULOS;

drop table if exists PAISES;

drop table if exists PRIVILEGIOS;

drop table if exists PRODUCTOS;

drop table if exists SUCURSALES;

drop table if exists SUCURSAL_POSEE_PRODUCTOS;

drop table if exists USUARIOS;

drop table if exists VENTAS;

drop table if exists VENTA_POSEE_PRODUCTOS;

/*==============================================================*/
/* Table: ARCHIVOS                                              */
/*==============================================================*/
create table ARCHIVOS
(
   ID_ARCHIVO           int not null auto_increment,
   ID_CONTENIDO         int,
   ID_BOLETIN           int,
   ID_PRODUCTO          int,
   NOMBRE_ARCHIVO       char(255),
   TIPO_ARCHIVO         char(255),
   DESTINO              char(255),
   DESCARGAS            int,
   COMENTARIO           char(255),
   primary key (ID_ARCHIVO)
);

alter table ARCHIVOS comment 'tipo de archivo: Imagen o archivo adjunto';

/*==============================================================*/
/* Table: BOLETIN                                               */
/*==============================================================*/
create table BOLETIN
(
   ID_BOLETIN           int not null auto_increment,
   NRO_EDICION          char(255),
   TITULO               char(255),
   CONTENIDO            longtext,
   TIPO_CONTENIDO       char(50),
   PLANTILLA            char(255),
   BANNER1              char(255),
   BANNER2              char(255),
   FECHA                date,
   FECHA_ENVIO          datetime,
   CLICK                int,
   ACTIVO               bool,
   primary key (ID_BOLETIN)
);

/*==============================================================*/
/* Table: CONTACTOS                                             */
/*==============================================================*/
create table CONTACTOS
(
   ID_CONTACTO          int not null auto_increment,
   TIPO_DOC             char(3),
   NRO_DOC              bigint,
   NOMBRE               char(255),
   APELLIDOS            char(255),
   NOMBRE_COMPLETO      char(255),
   SEXO                 char(1),
   DOMICILIO            char(255),
   TELEFONO_PARTICULAR  char(50),
   TELEFONO_MOVIL       char(50),
   CIUDAD               char(255),
   CODIGO_PAIS          char(2),
   EMAIL                char(255),
   PROFESION            char(255),
   NIVEL_ACADEMICO      char(50),
   AREA_ESTUDIO         char(255),
   INSTITUCION_TRABAJA  char(255),
   CARGO_TRABAJA        char(255),
   DOMICILIO_TRABAJA    char(255),
   TELEFONO_TRABAJA     char(50),
   COMENTARIOS          longtext,
   AREAS_INTERES        char(255),
   GRUPO                char(255),
   ID_ORIGEN            char(12),
   ACTIVO               bool,
   primary key (ID_CONTACTO)
);

alter table CONTACTOS comment 'seran los usuarios por sede y los que realizan consultas
                              -&';

/*==============================================================*/
/* Table: CONTACTOS_ACCEDEN                                     */
/*==============================================================*/
create table CONTACTOS_ACCEDEN
(
   ID_CONTACTO          int not null,
   ID_BOLETIN           int not null,
   FECHA                date,
   HORA                 time,
   primary key (ID_CONTACTO, ID_BOLETIN)
);

/*==============================================================*/
/* Table: CONTACTOS_DESCARGAN                                   */
/*==============================================================*/
create table CONTACTOS_DESCARGAN
(
   ID_CONTACTO          int not null,
   ID_ARCHIVO           int not null,
   FECHA                date,
   HORA                 time,
   primary key (ID_CONTACTO, ID_ARCHIVO)
);

/*==============================================================*/
/* Table: CONTACTOS_PERTENECE                                   */
/*==============================================================*/
create table CONTACTOS_PERTENECE
(
   ID_CONTACTO          int not null,
   ID_GRUPO             int not null,
   primary key (ID_CONTACTO, ID_GRUPO)
);

/*==============================================================*/
/* Table: CONTACTOS_RECIBEN                                     */
/*==============================================================*/
create table CONTACTOS_RECIBEN
(
   ID_CONTACTO          int not null,
   ID_BOLETIN           int not null,
   FECHAHORA            datetime,
   LEIDO                bool,
   primary key (ID_CONTACTO, ID_BOLETIN)
);

/*==============================================================*/
/* Table: CONTADOR                                              */
/*==============================================================*/
create table CONTADOR
(
   ID_CONTADOR          int not null auto_increment,
   ID_USUARIO           int,
   IP                   char(15),
   NOMBRE               char(255),
   IDIOMA               char(10),
   DATOS                char(255),
   FECHA                date,
   HORA                 time,
   QUERY_STRING         char(255),
   HTTP_REFERER         char(255),
   primary key (ID_CONTADOR)
);

/*==============================================================*/
/* Table: CONTENIDOS                                            */
/*==============================================================*/
create table CONTENIDOS
(
   ID_CONTENIDO         int not null auto_increment,
   ID_USUARIO           int not null,
   TIPO_CONTENIDO       char(255),
   CATEGORIA            char(255),
   TITULO               char(255),
   CONTENIDO            longtext,
   FECHA_ALTA           date,
   ACTIVO               bool,
   CLICK                int,
   TAGS_TITLE           char(255),
   TAGS_KEYWORDS        char(255),
   TAGS_DESCRIPTION     char(255),
   primary key (ID_CONTENIDO)
);

alter table CONTENIDOS comment 'tipo de contenido: internet, informatica, tienda virtual, pu';

/*==============================================================*/
/* Table: COTIZACIONES                                          */
/*==============================================================*/
create table COTIZACIONES
(
   ID_COTIZACION        int not null auto_increment,
   ID_SUCURSAL          int,
   ID_CLIENTE           int,
   ID_USUARIO           int,
   FECHA_ALTA           date,
   FECHA_VENCIMIENTO    date,
   TIPO_CAMBIO          decimal(10,2),
   DESCRIPCION          longtext,
   TOTAL                decimal(10,2),
   primary key (ID_COTIZACION)
);

/*==============================================================*/
/* Table: COTIZACION_POSEE_PRODUCTOS                            */
/*==============================================================*/
create table COTIZACION_POSEE_PRODUCTOS
(
   ID_COTIZACION        int not null,
   ID_PRODUCTO          int not null,
   CANTIDAD             int,
   PRECIO               decimal(10,2),
   DESCUENTO            decimal(10,2),
   TOTAL                decimal(10,2),
   primary key (ID_COTIZACION, ID_PRODUCTO)
);

/*==============================================================*/
/* Table: EGRESOS                                               */
/*==============================================================*/
create table EGRESOS
(
   ID_EGRESO            int not null auto_increment,
   ID_SUCURSAL          int,
   ID_CLIENTE           int,
   ID_USUARIO           int,
   FECHA_ALTA           date,
   NRO_FACTURA          char(10),
   TIPO_EGRESO          char(50),
   TIPO_CAMBIO          decimal(10,2),
   DESCRIPCION          longtext,
   TOTAL                decimal(10,2),
   PAGADO               bool,
   primary key (ID_EGRESO)
);

/*==============================================================*/
/* Table: EGRESO_POSEE_PRODUCTOS                                */
/*==============================================================*/
create table EGRESO_POSEE_PRODUCTOS
(
   ID_EGRESO            int not null,
   ID_PRODUCTO          int not null,
   CODIGO               char(10),
   DETALLE              char(255),
   PRECIO               decimal(10,2),
   CANTIDAD             int,
   TOTAL                decimal(10,2),
   primary key (ID_EGRESO, ID_PRODUCTO)
);

/*==============================================================*/
/* Table: FOTOGALERIA                                           */
/*==============================================================*/
create table FOTOGALERIA
(
   ID_FOTO              int not null auto_increment,
   CATEGORIA            char(255),
   NOMBRE               char(255),
   DESCRIPCION          longtext,
   FECHA_ALTA           date,
   ACTIVO               bool,
   CLICK                int,
   primary key (ID_FOTO)
);

/*==============================================================*/
/* Table: GRUPOS                                                */
/*==============================================================*/
create table GRUPOS
(
   ID_GRUPO             int not null auto_increment,
   NOMBRE               char(255),
   primary key (ID_GRUPO)
);

/*==============================================================*/
/* Table: INVENTARIOS                                           */
/*==============================================================*/
create table INVENTARIOS
(
   ID_INVENTARIO        int not null auto_increment,
   ID_SUCURSAL          int,
   ID_USUARIOVERIFICA   int,
   ID_USUARIO           int,
   FECHA_REALIZACION    date,
   DESCRIPCION          char(255),
   primary key (ID_INVENTARIO)
);

/*==============================================================*/
/* Table: INVENTARIO_POSEE_PRODUCTOS                            */
/*==============================================================*/
create table INVENTARIO_POSEE_PRODUCTOS
(
   ID_INVENTARIO        int not null,
   ID_PRODUCTO          int not null,
   STOCK                int,
   CANTIDAD             int,
   DIFERENCIA           int,
   primary key (ID_INVENTARIO, ID_PRODUCTO)
);

/*==============================================================*/
/* Table: MENSAJES                                              */
/*==============================================================*/
create table MENSAJES
(
   ID_MENSAJE           int not null auto_increment,
   TIPO_MENSAJE         char(255),
   NOMBRE               char(255),
   EMAIL                char(255),
   ASUNTO               char(255),
   MENSAJE              longtext,
   TIPO_CABEZA          char(255),
   ID_CABEZA            int,
   FECHA_ALTA           date,
   ACTIVO               bool,
   CODIGO_PAIS          char(2),
   primary key (ID_MENSAJE)
);

alter table MENSAJES comment 'tipo de mensajes: consultas, respuesta, comentarios o foros';

/*==============================================================*/
/* Table: MENU_MODULOS                                          */
/*==============================================================*/
create table MENU_MODULOS
(
   ID_MENU              int not null auto_increment,
   NOMBRE_MENU          char(255),
   CATEGORIA            char(255),
   URL                  char(255),
   ORDEN                int,
   TIPO                 char(20),
   primary key (ID_MENU)
);

/*==============================================================*/
/* Table: PAISES                                                */
/*==============================================================*/
create table PAISES
(
   CODIGO_PAIS          char(2) not null,
   PAIS                 char(255),
   primary key (CODIGO_PAIS)
);

/*==============================================================*/
/* Table: PRIVILEGIOS                                           */
/*==============================================================*/
create table PRIVILEGIOS
(
   ID_MENU              int not null,
   ID_USUARIO           int not null,
   VER                  bool,
   INSERTAR             bool,
   MODIFICAR            bool,
   ELIMINAR             bool,
   primary key (ID_MENU, ID_USUARIO)
);

/*==============================================================*/
/* Table: PRODUCTOS                                             */
/*==============================================================*/
create table PRODUCTOS
(
   ID_PRODUCTO          int not null auto_increment,
   CODIGO               char(10),
   CATEGORIA            char(255),
   NOMBRE               char(255),
   MARCA                char(255),
   DESCRIPCION          longtext,
   IMAGEN               char(255),
   PRECIO_COSTO         decimal(10,2),
   PRECIO_VENTA         decimal(10,2),
   STOCK_MINIMO         int,
   STOCK_MAXIMO         int,
   COMENTARIOS          longtext,
   ACTIVO               bool,
   primary key (ID_PRODUCTO)
);

/*==============================================================*/
/* Table: SUCURSALES                                            */
/*==============================================================*/
create table SUCURSALES
(
   ID_SUCURSAL          int not null auto_increment,
   NOMBRE               char(255),
   DOMICILIO            char(255),
   TELEFONO             char(255),
   ACTIVO               bool,
   primary key (ID_SUCURSAL)
);

/*==============================================================*/
/* Table: SUCURSAL_POSEE_PRODUCTOS                              */
/*==============================================================*/
create table SUCURSAL_POSEE_PRODUCTOS
(
   ID_SUCURSAL          int not null,
   ID_PRODUCTO          int not null,
   STOCK                int,
   primary key (ID_SUCURSAL, ID_PRODUCTO)
);

/*==============================================================*/
/* Table: USUARIOS                                              */
/*==============================================================*/
create table USUARIOS
(
   ID_USUARIO           int not null auto_increment,
   TIPO_DOC             char(3) not null,
   NRO_DOC              bigint not null,
   NIT                  char(20),
   NOMBRE               char(255),
   APELLIDOS            char(255),
   NOMBRE_COMPLETO      char(255),
   FECHA_NACIMIENTO     date,
   SEXO                 char(1),
   DOMICILIO            char(255),
   TELEFONO             char(50),
   TELEFONO_MOVIL       char(50),
   CIUDAD               char(255),
   CODIGO_PAIS          char(2),
   FOTO                 char(255),
   EMAIL                char(255),
   PROFESION            char(255),
   NIVEL_ACADEMICO      char(50),
   TIPO_USUARIO         char(30),
   USUARIO              char(255) not null,
   CLAVE                char(255) not null,
   CLAVE2               char(255),
   FECHA_ALTA           date,
   ACTIVO               bool,
   COMENTARIOS          longtext,
   CREDITOS             int,
   GRATIS               int,
   primary key (ID_USUARIO),
   key AK_USUARIOS_AK (APELLIDOS, NOMBRE),
   key AK_USUARIOS_AK2 (USUARIO)
);

alter table USUARIOS comment 'Tipos de Documento: DNI, CI, LC, LE, PSP
Tipos de Usua';

/*==============================================================*/
/* Table: VENTAS                                                */
/*==============================================================*/
create table VENTAS
(
   ID_VENTA             int not null auto_increment,
   ID_SUCURSAL          int,
   ID_CLIENTE           int,
   ID_USUARIO           int,
   FECHA_ALTA           date,
   NRO_FACTURA          char(10),
   TIPO_CAMBIO          decimal(10,2),
   DESCRIPCION          longtext,
   IMPORTE              decimal(10,2),
   IVA                  decimal(10,2),
   SUBTOTAL             decimal(10,2),
   DESCUENTO            decimal(10,2),
   TOTAL                decimal(10,2),
   RECIBIDO             decimal(10,2),
   SALDO                decimal(10,2),
   PAGADO               bool,
   IMPRESO              bool,
   primary key (ID_VENTA)
);

/*==============================================================*/
/* Table: VENTA_POSEE_PRODUCTOS                                 */
/*==============================================================*/
create table VENTA_POSEE_PRODUCTOS
(
   ID_PRODUCTO          int not null,
   ID_VENTA             int not null,
   CODIGO               char(10),
   DETALLE              char(255),
   PRECIO_COSTO         decimal(10,2),
   PRECIO_VENTA         decimal(10,2),
   CANTIDAD             int,
   DESCUENTO            decimal(10,2),
   TOTAL                decimal(10,2),
   primary key (ID_PRODUCTO, ID_VENTA)
);

alter table ARCHIVOS add constraint FK_BOLETIN_POSEE_ARCHIVOS foreign key (ID_BOLETIN)
      references BOLETIN (ID_BOLETIN) on delete restrict on update restrict;

alter table ARCHIVOS add constraint FK_CONTENIDOS_POSEEN_ARCHIVOS foreign key (ID_CONTENIDO)
      references CONTENIDOS (ID_CONTENIDO) on delete restrict on update restrict;

alter table ARCHIVOS add constraint FK_PRODUCTOS_POSEEN_ARCHIVOS foreign key (ID_PRODUCTO)
      references PRODUCTOS (ID_PRODUCTO) on delete restrict on update restrict;

alter table CONTACTOS add constraint FK_CONTACTO_PERTENECE1 foreign key (CODIGO_PAIS)
      references PAISES (CODIGO_PAIS) on delete restrict on update restrict;

alter table CONTACTOS_ACCEDEN add constraint FK_CONTACTOS_ACCEDEN foreign key (ID_CONTACTO)
      references CONTACTOS (ID_CONTACTO) on delete restrict on update restrict;

alter table CONTACTOS_ACCEDEN add constraint FK_CONTACTOS_ACCEDEN2 foreign key (ID_BOLETIN)
      references BOLETIN (ID_BOLETIN) on delete restrict on update restrict;

alter table CONTACTOS_DESCARGAN add constraint FK_CONTACTOS_DESCARGAN foreign key (ID_CONTACTO)
      references CONTACTOS (ID_CONTACTO) on delete restrict on update restrict;

alter table CONTACTOS_DESCARGAN add constraint FK_CONTACTOS_DESCARGAN2 foreign key (ID_ARCHIVO)
      references ARCHIVOS (ID_ARCHIVO) on delete restrict on update restrict;

alter table CONTACTOS_PERTENECE add constraint FK_CONTACTOS_PERTENECE foreign key (ID_CONTACTO)
      references CONTACTOS (ID_CONTACTO) on delete restrict on update restrict;

alter table CONTACTOS_PERTENECE add constraint FK_CONTACTOS_PERTENECE2 foreign key (ID_GRUPO)
      references GRUPOS (ID_GRUPO) on delete restrict on update restrict;

alter table CONTACTOS_RECIBEN add constraint FK_CONTACTOS_RECIBEN foreign key (ID_CONTACTO)
      references CONTACTOS (ID_CONTACTO) on delete restrict on update restrict;

alter table CONTACTOS_RECIBEN add constraint FK_CONTACTOS_RECIBEN2 foreign key (ID_BOLETIN)
      references BOLETIN (ID_BOLETIN) on delete restrict on update restrict;

alter table CONTADOR add constraint FK_CONTADOR_PERTENECE foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table CONTENIDOS add constraint FK_USUARIOS_REGISTRAN foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table COTIZACIONES add constraint FK_COTIZACION_PERTENECE_SUCURSAL foreign key (ID_SUCURSAL)
      references SUCURSALES (ID_SUCURSAL) on delete restrict on update restrict;

alter table COTIZACIONES add constraint FK_COTIZACION_PERTENECE_USUARIO foreign key (ID_CLIENTE)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table COTIZACIONES add constraint FK_USUARIO_REGISTRA_COTIZACION foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table COTIZACION_POSEE_PRODUCTOS add constraint FK_COTIZACION_POSEE_PRODUCTOS foreign key (ID_COTIZACION)
      references COTIZACIONES (ID_COTIZACION) on delete restrict on update restrict;

alter table COTIZACION_POSEE_PRODUCTOS add constraint FK_COTIZACION_POSEE_PRODUCTOS2 foreign key (ID_PRODUCTO)
      references PRODUCTOS (ID_PRODUCTO) on delete restrict on update restrict;

alter table EGRESOS add constraint FK_EGRESO_PERTENECE_SUCURSAL foreign key (ID_SUCURSAL)
      references SUCURSALES (ID_SUCURSAL) on delete restrict on update restrict;

alter table EGRESOS add constraint FK_EGRESO_PERTENECE_USUARIO foreign key (ID_CLIENTE)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table EGRESOS add constraint FK_USUARIO_REGISTRA_EGRESO foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table EGRESO_POSEE_PRODUCTOS add constraint FK_EGRESO_POSEE_PRODUCTOS foreign key (ID_EGRESO)
      references EGRESOS (ID_EGRESO) on delete restrict on update restrict;

alter table EGRESO_POSEE_PRODUCTOS add constraint FK_EGRESO_POSEE_PRODUCTOS2 foreign key (ID_PRODUCTO)
      references PRODUCTOS (ID_PRODUCTO) on delete restrict on update restrict;

alter table INVENTARIOS add constraint FK_INVENTARIO_PERTENECE_SUCURSAL foreign key (ID_SUCURSAL)
      references SUCURSALES (ID_SUCURSAL) on delete restrict on update restrict;

alter table INVENTARIOS add constraint FK_USUARIO_REGISTRA_INVENTARIO foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table INVENTARIOS add constraint FK_USUARIO_VERIFICA_INVENTARIO foreign key (ID_USUARIOVERIFICA)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table INVENTARIO_POSEE_PRODUCTOS add constraint FK_INVENTARIO_POSEE_PRODUCTOS foreign key (ID_INVENTARIO)
      references INVENTARIOS (ID_INVENTARIO) on delete restrict on update restrict;

alter table INVENTARIO_POSEE_PRODUCTOS add constraint FK_INVENTARIO_POSEE_PRODUCTOS2 foreign key (ID_PRODUCTO)
      references PRODUCTOS (ID_PRODUCTO) on delete restrict on update restrict;

alter table PRIVILEGIOS add constraint FK_PRIVILEGIOS foreign key (ID_MENU)
      references MENU_MODULOS (ID_MENU) on delete restrict on update restrict;

alter table PRIVILEGIOS add constraint FK_PRIVILEGIOS2 foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table SUCURSAL_POSEE_PRODUCTOS add constraint FK_SUCURSAL_POSEE_PRODUCTOS foreign key (ID_SUCURSAL)
      references SUCURSALES (ID_SUCURSAL) on delete restrict on update restrict;

alter table SUCURSAL_POSEE_PRODUCTOS add constraint FK_SUCURSAL_POSEE_PRODUCTOS2 foreign key (ID_PRODUCTO)
      references PRODUCTOS (ID_PRODUCTO) on delete restrict on update restrict;

alter table USUARIOS add constraint FK_USUARIOS_PERTENECEN foreign key (CODIGO_PAIS)
      references PAISES (CODIGO_PAIS) on delete restrict on update restrict;

alter table VENTAS add constraint FK_USUARIO_REGISTRA_VENTA foreign key (ID_USUARIO)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table VENTAS add constraint FK_VENTA_PERTENECE_SUCURSAL foreign key (ID_SUCURSAL)
      references SUCURSALES (ID_SUCURSAL) on delete restrict on update restrict;

alter table VENTAS add constraint FK_VENTA_PERTENECE_USUARIO foreign key (ID_CLIENTE)
      references USUARIOS (ID_USUARIO) on delete restrict on update restrict;

alter table VENTA_POSEE_PRODUCTOS add constraint FK_VENTA_POSEE_PRODUCTOS foreign key (ID_PRODUCTO)
      references PRODUCTOS (ID_PRODUCTO) on delete restrict on update restrict;

alter table VENTA_POSEE_PRODUCTOS add constraint FK_VENTA_POSEE_PRODUCTOS2 foreign key (ID_VENTA)
      references VENTAS (ID_VENTA) on delete restrict on update restrict;

