-- Paramétrage initial
START TRANSACTION;
DROP DATABASE IF EXISTS newblog;
CREATE DATABASE newblog;
USE newblog;

-- Creation of the users table
CREATE TABLE IF NOT EXISTS nb_user(
	id_user INT NOT NULL AUTO_INCREMENT,
	nickname VARCHAR(32) NOT NULL,
	password TEXT NOT NULL,
	is_mod BOOLEAN,
	register_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(id_user),
	UNIQUE KEY(nickname)
);

-- Creation of the blog settings table
CREATE TABLE IF NOT EXISTS nb_blog(
	blog_name VARCHAR(32) DEFAULT 'NewBlog',
	description TEXT NOT NULL DEFAULT 'My new blog!',
	logo_url TEXT NOT NULL DEFAULT '/img/logo.jpg',
	background_url TEXT NOT NULL DEFAULT '/img/background.jpg',
	creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	id_user_owner INT NOT NULL,
	PRIMARY KEY(blog_name),
	FOREIGN KEY(id_user_owner) REFERENCES nb_user(id_user)
);

-- Creation of the posts table
CREATE TABLE IF NOT EXISTS nb_post(
	id_post INT NOT NULL AUTO_INCREMENT,
	title VARCHAR(64) NOT NULL,
	summary TEXT NOT NULL,
	tags TEXT NOT NULL,
	content TEXT NOT NULL,
	time_stamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	id_user_author INT NOT NULL,
	PRIMARY KEY(id_post),
	FOREIGN KEY(id_user_author) REFERENCES nb_user(id_user)
);

-- For later:
-- /!\ Remove the tags attribute from the posts table
-- -- Creation of the tags table
-- CREATE TABLE IF NOT EXISTS nb_tag(
--    id_tag INT NOT NULL AUTO_INCREMENT,
--    tag_name VARCHAR(32) NOT NULL,
--    PRIMARY KEY(id_tag),
--    UNIQUE KEY(tag_name)
-- );

-- -- Creation of the table with tags associated with posts
-- CREATE TABLE IF NOT EXISTS nb_post_tag(
--    id_post INT NOT NULL,
--    id_tag INT NOT NULL,
--    PRIMARY KEY(id_post, id_tag),
--    FOREIGN KEY(id_post) REFERENCES nb_post(id_post),
--    FOREIGN KEY(id_tag) REFERENCES nb_tag(id_tag)
-- );

COMMIT; -- End of transaction
