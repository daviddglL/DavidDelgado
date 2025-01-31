

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

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(45) NOT NULL,
    precio FLOAT NOT NULL,
    descripcion VARCHAR(100) NOT NULL,
    stock INT NOT NULL,
    estado ENUM('disponible', 'no disponible') NOT NULL,
    imagen VARCHAR(100) NOT NULL,
    membresia TINYINT(1) NOT NULL DEFAULT 1
);


-- Creating 'citas' table
CREATE TABLE citas (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    codigo_socio INT,
    codigo_servicio INT,
    fecha_cita DATE NOT NULL,
    hora_cita TIME NOT NULL,
    estado ENUM('pendiente', 'anulada', 'completada') DEFAULT 'pendiente',
    FOREIGN KEY (codigo_socio) REFERENCES socio(id_socio),
    FOREIGN KEY (codigo_servicio) REFERENCES servicio(codigo_servicio)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    api_key VARCHAR(64) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


INSERT INTO users (nombre, email, password, role) VALUES
('Admin', 'admin@example.com', SHA2('adminpassword', 256), 'admin'),
('Usuario1', 'user1@example.com', SHA2('userpassword', 256), 'user');



-- Inserting sample data into 'socio' table
INSERT INTO socio (nombre, edad, contrasena, usuario, telefono, foto) VALUES
('Anonimo', 100, 'contraseña1234', 'anonimo', '159753486', 'anonimo'),
('Carlos Perez', 30, 'password123', 'cperez', '123456789', 'hombre1'),
('Ana Lopez', 25, 'securepass', 'alopez', '987654321', 'mujer1'),
('Luis Gomez', 28, 'mypassword', 'lgomez', '456123789', 'hombre2'),
('Mariana Torres', 32, 'password456', 'mtorres', '159753486', 'mujer2'),
('Javier Morales', 22, 'pass789', 'jmorales', '753951486', 'hombre3');

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
(3, 'La calistenia avanzada es intensa, pero vale la pena.', '2024-11-03'),
(1, 'Las instalaciones del club son de primera calidad, me encanta venir aquí.', '2024-11-04'),
(2, 'El personal siempre es amable y está dispuesto a ayudar.', '2024-11-05'),
(3, 'Gracias a las clases de yoga, me siento más relajado y en forma.', '2024-11-06'),
(4, 'Las actividades grupales me han ayudado a conocer a personas increíbles.', '2024-11-07'),
(5, 'Los entrenadores son muy profesionales y atentos.', '2024-11-08'),
(1, 'Nunca pensé que disfrutaría tanto de las clases de spinning.', '2024-11-09'),
(2, 'Las sesiones de HIIT son intensas pero me hacen sentir súper energizado.', '2024-11-10'),
(3, 'He mejorado mi resistencia gracias a las rutinas personalizadas.', '2024-11-11'),
(4, 'Me encanta que el club siempre tenga actividades nuevas para probar.', '2024-11-12'),
(5, 'El ambiente en el club es motivador y acogedor.', '2024-11-13');


-- Inserting sample data into 'noticia' table
INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion) VALUES
('Primera Carrera Anual del Club Deportivo',
 'Nuestro club deportivo se complace en anunciar la celebración de nuestra Primera Carrera Anual, un evento diseñado para fomentar la actividad física, fortalecer los lazos comunitarios y disfrutar de una experiencia llena de entusiasmo y energía. La carrera se llevará a cabo el 20 de noviembre e incluirá circuitos de 5K y 10K, cuidadosamente diseñados para adaptarse a participantes de todos los niveles de condición física, desde principiantes hasta corredores experimentados.
 Además del desafío físico, queremos que este sea un evento inolvidable. Por ello, al finalizar la carrera, los participantes serán recibidos con refrescos y snacks saludables para recargar energías. También se realizará una ceremonia de premiación, donde se entregarán medallas conmemorativas para todos los que crucen la meta y premios especiales para los ganadores de cada categoría.\n  
 Para garantizar una organización óptima, las inscripciones están abiertas en la recepción del club y cerrarán el 19 de noviembre. Los participantes inscritos recibirán un kit que incluye un número oficial, una camiseta exclusiva del evento y acceso a una charla previa sobre técnicas de carrera y calentamiento, impartida por nuestros entrenadores expertos.  
 Invitamos a todos nuestros socios y a sus familias a unirse a esta gran celebración deportiva. Ya sea participando en los circuitos, animando desde las gradas o simplemente compartiendo la experiencia, ¡todos son bienvenidos! No te pierdas esta oportunidad de ser parte de un evento que promete convertirse en una tradición anual. ¡Corre con nosotros y haz historia en nuestro club!',
 'carrera', '2024-11-01'),

('Semana de Puertas Abiertas para Nuevos Miembros',
 'Del 6 al 12 de noviembre, nuestro club estará celebrando su tan esperada Semana de Puertas Abiertas. Durante estos días, queremos compartir con toda la comunidad la oportunidad de descubrir lo que hace especial a nuestro club, ofreciendo acceso completamente gratuito a nuestras instalaciones y actividades. Este evento es ideal para quienes deseen conocer nuestro enfoque integral de entrenamiento y bienestar.
 Entre las actividades destacadas, los visitantes podrán explorar nuestras áreas de entrenamiento, probar equipos de última generación y participar en clases de prueba de disciplinas como calistenia, yoga y entrenamiento funcional. Además, ofreceremos sesiones especiales de pilates y estiramientos, diseñadas para brindar una muestra de los beneficios físicos y mentales que estas prácticas pueden ofrecer. \n
 Como parte del evento, hemos preparado demostraciones exclusivas de nuestros programas de entrenamiento más populares, guiadas por nuestros instructores certificados. También estarán disponibles sesiones de orientación individualizadas, donde nuestros entrenadores responderán preguntas sobre planes de entrenamiento, nutrición y servicios personalizados para ayudar a los participantes a alcanzar sus metas de salud.
 Invitamos a nuestros socios a compartir esta experiencia con amigos y familiares, permitiéndoles disfrutar de un entorno dinámico y motivador. No pierdas esta oportunidad de mostrarles cómo el deporte y el bienestar pueden ser parte de su día a día. ¡Te esperamos para vivir juntos esta celebración!',
 'puertas-abiertas', '2024-11-03'),

('Renovación de las Instalaciones de Pesas Libres',
 'Con el objetivo de mejorar la experiencia de nuestros socios, el área de pesas libres estará siendo renovada entre el 13 y el 15 de noviembre. Durante este periodo, se llevará a cabo la instalación de nuevos equipos de última tecnología que buscan satisfacer las necesidades tanto de principiantes como de usuarios avanzados. Entre las mejoras destacan la incorporación de racks ajustables, barras olímpicas de alto rendimiento, y una amplia variedad de mancuernas de diferentes pesos, diseñadas para cubrir todos los niveles de entrenamiento.
 Además, se realizará una reestructuración del espacio para optimizar la distribución de los equipos, permitiendo un flujo más cómodo y seguro durante los entrenamientos. También se instalará un nuevo sistema de ventilación y mejoras en la iluminación, creando un ambiente más agradable y funcional para el desarrollo de las actividades físicas.   \n
 Entendemos que el cierre temporal del área pueda generar inconvenientes, por lo que agradecemos de antemano la comprensión de nuestros socios. Queremos asegurarte que estas mejoras están pensadas para ofrecerte un espacio de entrenamiento más moderno, seguro y eficiente, que esté a la altura de tus objetivos de acondicionamiento físico. 
 Te invitamos a utilizar las otras áreas del club durante estos días y, si tienes dudas o necesitas recomendaciones sobre cómo ajustar tu rutina mientras se completan los trabajos, nuestro equipo estará encantado de ayudarte. Estamos emocionados de compartir contigo este nuevo espacio a partir del 16 de noviembre, ¡y esperamos que disfrutes de todos los beneficios que traerá!',
 'pesas', '2024-11-05'),

('Conferencia sobre Nutrición Deportiva con el Dr. Luis Ramírez',
 'Este 16 de noviembre, nuestro club tiene el honor de recibir al Dr. Luis Ramírez, un destacado especialista en nutrición deportiva con más de 15 años de experiencia asesorando a atletas de élite y aficionados. Durante su visita, el Dr. Ramírez ofrecerá una conferencia titulada "Alimentación y Rendimiento Físico", en la que abordará los principios fundamentales de una dieta equilibrada diseñada para optimizar el rendimiento en el deporte y promover una salud integral. 
 En esta charla, los asistentes tendrán la oportunidad de aprender cómo ajustar su alimentación para maximizar la energía antes del ejercicio, acelerar la recuperación después de entrenamientos intensos y mejorar la resistencia en actividades de alto rendimiento. Además, se discutirán temas como la hidratación adecuada, el uso de suplementos y estrategias para adaptar las dietas según los diferentes tipos de deportes y objetivos personales.  \n  
 El Dr. Ramírez también compartirá estudios de casos prácticos y brindará consejos específicos sobre cómo superar desafíos comunes relacionados con la nutrición, como el manejo de lesiones o la preparación para competencias importantes. Al final de la conferencia, se abrirá un espacio para una sesión de preguntas y respuestas, donde los participantes podrán resolver dudas y recibir recomendaciones personalizadas. 
 La entrada a este evento será completamente gratuita para los socios del club, pero debido a la alta demanda, los cupos son limitados. Te invitamos a asegurar tu lugar registrándote en recepción antes del 14 de noviembre. No pierdas la oportunidad de adquirir conocimientos valiosos que podrán marcar una diferencia en tu desempeño deportivo. ¡Te esperamos!',
 'nutricion', '2024-11-07'),

('Clase Especial de Entrenamiento Funcional para Adultos Mayores',
 'Como parte de nuestro compromiso con el bienestar de todos nuestros socios, el 20 de noviembre estaremos ofreciendo una clase especial de entrenamiento funcional enfocada en adultos mayores. Esta sesión, diseñada específicamente para cubrir las necesidades de esta etapa de la vida, se centrará en ejercicios de bajo impacto que ayudarán a mejorar la movilidad, el equilibrio, la fuerza y la coordinación. Estas habilidades son fundamentales no solo para mantener una vida activa y saludable, sino también para prevenir caídas y otros problemas asociados con la pérdida de movilidad. 
  Durante la sesión, nuestros instructores certificados, con amplia experiencia en trabajo con adultos mayores, guiarán a los participantes en cada ejercicio, asegurándose de que se realicen de forma segura y efectiva. Además, se ofrecerán adaptaciones personalizadas para que cada asistente pueda trabajar a su propio ritmo y nivel de capacidad física, promoviendo un ambiente inclusivo y accesible para todos.   \n  
  Al final de la clase, se dedicará un tiempo a resolver dudas y compartir consejos sobre cómo incorporar estos ejercicios en la vida diaria. También se proporcionará una guía con recomendaciones para mantener una rutina de ejercicios regular en casa, fortaleciendo el compromiso con la salud y el bienestar a largo plazo. 
  Si tienes familiares interesados en participar, te invitamos a animarlos a inscribirse en recepción antes del 18 de noviembre, ya que los cupos son limitados. Esta es una oportunidad única para disfrutar de un momento de actividad física supervisada, rodeado de un ambiente de camaradería y apoyo mutuo. ¡No te lo pierdas!',
 'entrenamiento-personas-mayores', '2024-11-10');



INSERT INTO productos (nombre, precio, descripcion, stock, estado, imagen, membresia) VALUES
('Camiseta Deportiva', 19.99, 'Camiseta de algodón 100% para entrenamiento.', 50, 'disponible', 'camiseta.jpg', 1),
('Balón de Fútbol', 29.99, 'Balón de fútbol oficial tamaño 5.', 30, 'disponible', 'balon.jpg', 0),
('Botella de Agua', 9.50, 'Botella de agua reutilizable de 750ml.', 100, 'disponible', 'botella.jpg', 1),
('Mancuernas', 49.99, 'Par de mancuernas de 10 kg cada una.', 20, 'disponible', 'mancuernas.jpg', 1),
('Toalla de Entrenamiento', 12.00, 'Toalla ligera y absorbente.', 75, 'disponible', 'toalla.jpg', 0),
('Gorra Deportiva', 14.99, 'Gorra ajustable con logo del club.', 40, 'no disponible', 'gorra.jpg', 1),
('Zapatillas Running', 89.99, 'Zapatillas para correr con amortiguación avanzada.', 15, 'disponible', 'zapatillas.jpg', 1),
('Cuerda para Saltar', 7.99, 'Cuerda de saltar ajustable con agarres cómodos.', 200, 'disponible', 'cuerda.jpg', 0),
('Chándal Completo', 59.99, 'Chándal de dos piezas, ideal para invierno.', 10, 'no disponible', 'chandal.jpg', 1),
('Raqueta de Tenis', 99.99, 'Raqueta de tenis profesional.', 8, 'disponible', 'raqueta.jpg', 1),
('Protector Bucal', 5.50, 'Protector bucal para deportes de contacto.', 150, 'disponible', 'protector.jpg', 0),
('Banda de Resistencia', 11.99, 'Banda de resistencia de nivel medio.', 80, 'disponible', 'banda.jpg', 0);


-- Inserting sample data into 'citas' table
INSERT INTO citas (codigo_socio, codigo_servicio, fecha_cita, hora_cita) VALUES
(1, 1, '2024-11-06', '10:00:00'),
(2, 2, '2024-11-07', '14:00:00'),
(3, 3, '2024-11-08', '16:00:00'),
(4, 1, '2024-11-09', '09:00:00'),
(5, 2, '2024-11-10', '12:00:00');
