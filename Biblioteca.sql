CREATE TABLE Estudiantes(
  id int NOT NULL AUTO_INCREMENT,
  Nombre varchar(60) NOT NULL,
  Apellido varchar(60) NOT NULL,
  Matricula varchar(60) NOT NULL,
  Cedula varchar(60) NOT NULL,
  Sexo enum('F','M') NOT NULL,
  Telefono varchar(40) NOT NULL,
  Email varchar(100) NOT NULL, 
  PRIMARY key (id));
  

insert into estudiantes(Nombre, Apellido, Matricula, Cedula, Sexo, Telefono, Email) values
('Juan', 'Pérez', '2023001', '001-1234567-8', 'M', '809-555-1234', 'juan.perez@email.com'),
('María', 'Gómez', '2023002', '002-7654321-9', 'F', '829-444-5678', 'maria.gomez@email.com'),
('Carlos', 'Ramírez', '2023003', '003-9876543-2', 'M', '849-333-9012', 'carlos.ramirez@email.com'),
('Ana', 'Martínez', '2023004', '004-5432198-7', 'F', '809-222-3456', 'ana.martinez@email.com'),
('Pedro', 'Fernández', '2023005', '005-6789123-5', 'M', '829-111-7890', 'pedro.fernandez@email.com');


create table Registro (
  id int not null auto_increment,
  nombre varchar(60) not null,
  apellido varchar(60) not null,
  cedula varchar(60) not null,
  matricula varchar(60) not null,
  tipo_de_solicitud enum(
    'Realizar Tarea', 
    'Uso de Computador', 
    'Solicitud de Libro', 
    'Devolucion de Libro', 
    'Uso de Cubiculo', 
    'Visitas', 
    'Proceso de Admision', 
    'Seleccion de Asignatura', 
    'Orientacion en el Uso de Plataformas de Estudio', 
    'Otros'
  ) default null,
  sexo enum('F', 'M') not null,
  telefono varchar(40) not null,
  email varchar(100) not null,
  fecha_de_ingreso timestamp,
  tipo_de_persona enum('Estudiante', 'Visitante', 'Administrativo', 'Maestros') not null,
  primary key (id)
);

  
insert into Registro (nombre, apellido, cedula, matricula, tipo_de_solicitud, sexo, telefono, email, fecha_de_ingreso, tipo_de_persona) values
('Juan', 'Pérez', '001-1234567-8', '2001-01', 'Realizar Tarea', 'M', '809-555-1234', 'juan.perez@email.com', '2025-03-01 14:00:00', 'Estudiante'),
('María', 'Gómez', '002-7654321-9', '2002-02', 'Uso de Computador', 'F', '829-444-5678', 'maria.gomez@email.com', '2025-03-02 15:30:00', 'Estudiante'),
('Carlos', 'Ramírez', '003-9876543-2', '2003-03', 'Proceso de Admision', 'M', '849-333-9012', 'carlos.ramirez@email.com', '2025-03-03 19:45:00', 'Visitante'),
('Ana', 'Martínez', '004-5432198-7', '2004-04', 'Otros', 'F', '809-222-3456', 'ana.martinez@email.com', '2025-03-04 13:20:00', 'Maestros');







  