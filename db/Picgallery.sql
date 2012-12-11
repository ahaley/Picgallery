CREATE TABLE picgallery_image (
    name VARCHAR(64) NOT NULL,
    gallery VARCHAR(64) NOT NULL,
    url VARCHAR(255) NOT NULL,
    thumbnail_url VARCHAR(255) NOT NULL,
    PRIMARY KEY(name, gallery)
) ENGINE=INNODB;

