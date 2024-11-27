SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

create table if not exists secciones
(
    idSeccion   int auto_increment
        primary key,
    nombre      varchar(50)  not null,
    ubicacion varchar(200) not null
);

create table if not exists inventario
(
    idInventario int auto_increment
        primary key,
    parte        varchar(100)   not null,
    marca        varchar(50)    not null,
    modelo       varchar(30)    not null,
    fecha        int            not null,
    cantidad     int            not null,
    costo        decimal(10, 2) not null,
    idSeccion    int            not null,
    imagen       varchar(255)       null,
    descripcion  varchar(255)   not null,
    constraint inventario_ibfk_1
        foreign key (idSeccion) references secciones (idSeccion)
);

create index idSeccion
    on inventario (idSeccion);

create table if not exists movimientos_inventario
(
    idMovimiento    int auto_increment
        primary key,
    idInventario    int                        not null,
    tipoMovimiento  enum ('entrada', 'salida') not null,
    cantidad        int                        not null,
    fechaMovimiento datetime                   not null,
    constraint movimientos_inventario_ibfk_1
        foreign key (idInventario) references inventario (idInventario)
);

create index idInventario
    on movimientos_inventario (idInventario);

create table if not exists usuarios
(
    idUsuario  int auto_increment
        primary key,
    usuario    varchar(50)          not null,
    apellido   varchar(50)          not null,
    contrasena varchar(200)         not null,
    correo     varchar(100)         not null,
    cedula     varchar(100),
    activo     tinyint(1) default 1 not null
);