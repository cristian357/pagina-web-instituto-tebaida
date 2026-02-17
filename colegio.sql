DROP DATABASE IF EXISTS colegio;
CREATE DATABASE colegio;
USE colegio;

CREATE TABLE profesor (
    id_profesor INT PRIMARY KEY,
    nombre_profesor VARCHAR(50),
    apellido_profesor VARCHAR(50),
    telefono_profesor VARCHAR(10),
    correo_profesor VARCHAR(50) UNIQUE,
    contrasena_profe VARCHAR(250)
);

CREATE TABLE grupo (
    id_grupo INT PRIMARY KEY,
    nombre_grupo VARCHAR(50)
);

CREATE TABLE materia (
    id_materia INT PRIMARY KEY,
    nombre_materia VARCHAR(50),
    id_profesor INT, 
    grado_materia INT,
    FOREIGN KEY (id_profesor) REFERENCES profesor(id_profesor)

);

CREATE TABLE estudiante (
    id_estudiante INT PRIMARY KEY,
    nombre_estudiante VARCHAR(50),
    apellido_estudiante VARCHAR(50),
    telefono_estudiante VARCHAR(10),
    direccion_estudiante VARCHAR(100),
    correo_estudiante VARCHAR(50) UNIQUE,
    contrasena_estudiante VARCHAR(255),
    id_grupo INT, 
    FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo)
);

CREATE TABLE grupo_materia (
    id_grupo INT,
    id_materia INT,
    PRIMARY KEY (id_grupo, id_materia),
    FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo),
    FOREIGN KEY (id_materia) REFERENCES materia(id_materia)
);

CREATE TABLE nota (
    id_nota INT AUTO_INCREMENT PRIMARY KEY,
    nota DECIMAL(5,2),
    id_materia INT,
    id_estudiante INT,
    periodo INT(2),
    es_recuperacion TINYINT(1) DEFAULT 0,
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante),
    FOREIGN KEY (id_materia) REFERENCES materia(id_materia)
);
CREATE TABLE falta (
    id_falta INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE,
    hora TIME(6),
    tipo VARCHAR(50),
    id_materia INT,
    id_estudiante INT,
    FOREIGN KEY (id_materia) REFERENCES materia(id_materia),
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante)
);

CREATE TABLE anotacion (
    id_anotacion INT AUTO_INCREMENT PRIMARY KEY,
    porque VARCHAR(255),
    id_materia INT,
    id_estudiante INT,
    fecha DATE,
    duracion DATE,
    FOREIGN KEY (id_materia) REFERENCES materia(id_materia),
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante)
);



CREATE TABLE nota_final (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_materia INT NOT NULL,
    periodo INT NOT NULL,
    promedio DECIMAL(3,2) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante),
    FOREIGN KEY (id_materia) REFERENCES materia(id_materia)
);

CREATE TABLE suspension (
    id_suspension INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    motivo VARCHAR(255) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    observaciones TEXT,
    impuesta_por VARCHAR(100), -- nombre o cargo del profesor/coordinador que la impone
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante)
);


SELECT 
    m.nombre_materia, 
    nf.periodo, 
    nf.promedio,
    MAX(CASE WHEN n.es_recuperacion = 1 THEN n.nota ELSE NULL END) AS recuperacion
FROM nota_final nf
INNER JOIN materia m ON m.id_materia = nf.id_materia
LEFT JOIN nota n 
    ON n.id_estudiante = nf.id_estudiante 
    AND n.id_materia = nf.id_materia 
    AND n.periodo = nf.periodo 
    AND n.es_recuperacion = 1
GROUP BY m.nombre_materia, nf.periodo, nf.promedio;

