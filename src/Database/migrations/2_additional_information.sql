CREATE TABLE IF NOT EXISTS additional_information
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    person_id     INT NOT NULL,
    company       VARCHAR(255),
    position      VARCHAR(255),
    about_me      TEXT,
    path_to_photo VARCHAR(255),
    UNIQUE (path_to_photo),
    FOREIGN KEY (person_id)
     REFERENCES persons (id)
)