CREATE TABLE persons
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    first_name     VARCHAR(100) NOT NULL,
    last_name      VARCHAR(100) NOT NULL,
    birthdate      DATE         NOT NULL,
    report_subject TEXT         NOT NULL,
    country        VARCHAR(255) NOT NULL,
    phone          VARCHAR(17)  NOT NULL,
    email          VARCHAR(255) NOT NULL,
    UNIQUE (email)
)