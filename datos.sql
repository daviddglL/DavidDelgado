CREATE DATABASE club_primos;

-- Dropping tables if they exist
DROP TABLE IF EXISTS citas;
DROP TABLE IF EXISTS testimonio;
DROP TABLE IF EXISTS noticia;
DROP TABLE IF EXISTS servicio;
DROP TABLE IF EXISTS socio;

-- Creating 'socio' table
CREATE TABLE socio (
    id_socio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    edad INT NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    foto VARCHAR(255)
);

-- Creating 'servicio' table
CREATE TABLE servicio (
    codigo_servicio INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    duracion_servicio TIME NOT NULL,
    precio_servicio DECIMAL(10, 2) NOT NULL
);

-- Creating 'testimonio' table
CREATE TABLE testimonio (
    id_testimonio INT AUTO_INCREMENT PRIMARY KEY,
    autor INT,
    contenido TEXT NOT NULL,
    fecha DATE NOT NULL,
    FOREIGN KEY (autor) REFERENCES socio(id_socio)
);

-- Creating 'noticia' table
CREATE TABLE noticia (
    id_noticia INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    contenido TEXT NOT NULL,
    imagen VARCHAR(255),
    fecha_publicacion DATE NOT NULL
);

-- Creating 'citas' table
CREATE TABLE citas (
    codigo_socio INT,
    codigo_servicio INT,
    fecha_cita DATE NOT NULL,
    hora_cita TIME NOT NULL,
    PRIMARY KEY (codigo_socio, codigo_servicio, fecha_cita, hora_cita),
    FOREIGN KEY (codigo_socio) REFERENCES socio(id_socio),
    FOREIGN KEY (codigo_servicio) REFERENCES servicio(codigo_servicio)
);

-- Inserting sample data into 'socio' table
INSERT INTO socio (nombre, edad, contrasena, usuario, telefono, foto) VALUES
('Carlos Perez', 30, 'password123', 'cperez', '123456789', 'img/socios/preview (2).webp'),
('Ana Lopez', 25, 'securepass', 'alopez', '987654321', 'img/socios/preview (1).webp'),
('Luis Gomez', 28, 'mypassword', 'lgomez', '456123789', 'img/socios/preview.webp'),
('Mariana Torres', 32, 'password456', 'mtorres', '159753486', 'img/socios/preview.webp'),
('Javier Morales', 22, 'pass789', 'jmorales', '753951486', 'img/socios/preview.webp');

-- Inserting sample data into 'servicio' table
INSERT INTO servicio (descripcion, duracion_servicio, precio_servicio) VALUES
('Yoga Restaurativo', '01:15:00', 18.00),
('Entrenamiento Funcional', '01:30:00', 22.00),
('Boxeo para Principiantes', '01:00:00', 15.00),
('Circuito de Fuerza Avanzado', '02:00:00', 28.00),
('Pilates para Todos', '01:20:00', 20.00);


-- Inserting sample data into 'testimonio' table
INSERT INTO testimonio (autor, contenido, fecha) VALUES
(1, 'Me encantó la clase de calistenia, muy motivadora.', '2024-11-01'),
(2, 'Las sesiones de calistenia intermedia son un desafío agradable.', '2024-11-02'),
(3, 'La calistenia avanzada es intensa, pero vale la pena.', '2024-11-03');

-- Inserting sample data into 'noticia' table
INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion) VALUES
('Primera Carrera Anual del Club Deportivo',
 'Nuestro club deportivo se complace en anunciar la celebración de nuestra Primera Carrera Anual, un evento pensado para unir a la comunidad en torno a la actividad física y la diversión. La carrera, que se realizará el 20 de noviembre, contará con circuitos de 5K y 10K, diseñados para adaptarse a todos los niveles de condición física. \n\nLos participantes podrán disfrutar de refrescos y snacks al finalizar la carrera, y se entregarán medallas y premios a los ganadores de cada categoría. Las inscripciones están abiertas en la recepción del club y cerrarán el día anterior al evento. ¡Invitamos a todos los socios a sumarse y celebrar con nosotros este primer gran evento deportivo!',
 'img/news/images.jpg', '2024-11-01'),

('Semana de Puertas Abiertas para Nuevos Miembros',
 'Del 6 al 12 de noviembre, nuestro club estará celebrando su Semana de Puertas Abiertas. Durante estos días, todas las personas interesadas en conocer nuestras instalaciones podrán hacerlo sin costo alguno. Podrán acceder a nuestras áreas de entrenamiento, participar en clases de prueba y descubrir los beneficios de ser socio. \n\nOfreceremos demostraciones de calistenia, yoga, entrenamiento funcional, y también habrá sesiones de orientación con nuestros entrenadores para resolver todas las dudas sobre los servicios. ¡Invita a tus amigos y familiares para que vivan la experiencia de nuestro club deportivo!',
 'img/news/images.jpg', '2024-11-03'),

('Renovación de las Instalaciones de Pesas Libres',
 'Con el objetivo de mejorar la experiencia de nuestros socios, el área de pesas libres estará siendo renovada entre el 13 y el 15 de noviembre. Durante estos días, se instalarán nuevos equipos de última tecnología, incluyendo racks ajustables, barras olímpicas y mancuernas de varias categorías para satisfacer las necesidades de todos los niveles.\n\nAgradecemos la comprensión de nuestros socios, ya que el área permanecerá cerrada mientras se realicen las mejoras. Estamos comprometidos en ofrecer un espacio seguro, cómodo y con lo mejor en tecnología para ayudar a cada socio a alcanzar sus metas de acondicionamiento físico.',
 'img/news/images.jpg', '2024-11-05'),

('Conferencia sobre Nutrición Deportiva con el Dr. Luis Ramírez',
 'Este 16 de noviembre, nuestro club tiene el honor de recibir al Dr. Luis Ramírez, un destacado especialista en nutrición deportiva. El Dr. Ramírez ofrecerá una conferencia titulada "Alimentación y Rendimiento Físico", en la que hablará sobre los fundamentos de una dieta equilibrada para optimizar el rendimiento en el deporte.\n\nDurante la charla, los asistentes aprenderán sobre cómo ajustar su dieta para mejorar su energía, recuperación y resistencia en diversas disciplinas deportivas. La entrada será gratuita para socios, pero el cupo es limitado. Asegura tu lugar registrándote en la recepción.',
 'img/news/images.jpg', '2024-11-07'),

('Clase Especial de Entrenamiento Funcional para Adultos Mayores',
 'Como parte de nuestro compromiso con el bienestar de todos nuestros socios, el 20 de noviembre estaremos ofreciendo una clase especial de entrenamiento funcional enfocada en adultos mayores. Esta sesión se centrará en ejercicios de bajo impacto diseñados para mejorar la movilidad, el equilibrio y la fuerza, fundamentales para una vida activa y saludable.\n\nNuestros instructores certificados guiarán a los participantes en cada ejercicio, asegurando que se realicen de forma segura y efectiva. Si tienes familiares interesados en participar, anímalos a inscribirse en recepción y aprovechar esta oportunidad única.',
 'img/news/images.jpg', '2024-11-10');


-- Inserting sample data into 'citas' table
INSERT INTO citas (codigo_socio, codigo_servicio, fecha_cita, hora_cita) VALUES
(1, 1, '2024-11-06', '10:00:00'),
(2, 2, '2024-11-07', '14:00:00'),
(3, 3, '2024-11-08', '16:00:00'),
(4, 1, '2024-11-09', '09:00:00'),
(5, 2, '2024-11-10', '12:00:00');
