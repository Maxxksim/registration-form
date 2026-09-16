CREATE TABLE IF NOT EXISTS members
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    first_name     VARCHAR(100) NOT NULL,
    last_name      VARCHAR(100) NOT NULL,
    birthdate      DATE         NOT NULL,
    report_subject VARCHAR(255)         NOT NULL,
    country        VARCHAR(255) NOT NULL,
    phone          VARCHAR(17)  NOT NULL,
    email          VARCHAR(255) NOT NULL UNIQUE,
    company        VARCHAR(255),
    position       VARCHAR(255),
    about_me       TEXT,
    path_to_photo  VARCHAR(255)
)