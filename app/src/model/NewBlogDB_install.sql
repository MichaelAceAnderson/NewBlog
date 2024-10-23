-- Paramétrage initial
START TRANSACTION;
DROP SCHEMA IF EXISTS newblog CASCADE;
CREATE SCHEMA IF NOT EXISTS newblog;
SET SCHEMA 'newblog';
SET TIME ZONE 'Europe/Paris';

-- Creation of the users table
CREATE SEQUENCE IF NOT EXISTS user_seq; -- Counter incremented with each new user
ALTER SEQUENCE user_seq RESTART WITH 1; -- Restart the counter at 1
CREATE TABLE IF NOT EXISTS nb_user(
	id_user INT NOT NULL DEFAULT NEXTVAL ('user_seq'),
	nickname VARCHAR(32) NOT NULL,
	password TEXT NOT NULL,
	is_mod BOOLEAN,
	register_date TIMESTAMP(0) NOT NULL DEFAULT now(),
	CONSTRAINT PK_nb_user PRIMARY KEY(id_user),
	CONSTRAINT AK_nb_user UNIQUE(nickname)
);

-- Creation of the blog settings table
CREATE TABLE IF NOT EXISTS nb_blog(
	blog_name VARCHAR(32) DEFAULT 'NewBlog',
	description TEXT NOT NULL DEFAULT 'My new blog!',
	logo_url TEXT NOT NULL DEFAULT '/img/logo.jpg',
	background_url TEXT NOT NULL DEFAULT '/img/background.jpg',
	creation_date TIMESTAMP(0) NOT NULL DEFAULT now(),
	id_user_owner INT NOT NULL,
	CONSTRAINT PK_nb_blog_name PRIMARY KEY(blog_name),
	CONSTRAINT FK_nb_blog_nb_user_owner FOREIGN KEY(id_user_owner) REFERENCES nb_user(id_user)
);

-- Creation of the posts table
CREATE SEQUENCE IF NOT EXISTS post_seq; -- Counter incremented with each new post
ALTER SEQUENCE post_seq RESTART WITH 1; -- Restart the counter at 1
CREATE TABLE IF NOT EXISTS nb_post(
	id_post INT DEFAULT NEXTVAL ('post_seq'),
	title VARCHAR(64) NOT NULL,
	summary TEXT NOT NULL,
	tags TEXT NOT NULL,
	content TEXT NOT NULL,
	time_stamp TIMESTAMP(0) NOT NULL DEFAULT now(),
	id_user_author INT NOT NULL,
	CONSTRAINT PK_nb_post PRIMARY KEY(id_post),
	CONSTRAINT FK_nb_post_nb_user_author FOREIGN KEY(id_user_author) REFERENCES nb_user(id_user)
);

-- For later:
-- /!\ Remove the tags attribute from the posts table
-- -- Creation of the tags table
-- CREATE TABLE IF NOT EXISTS nb_tag(
--    id_tag INT NOT NULL,
--    tag_name VARCHAR(32) NOT NULL,
--    CONSTRAINT PK_nb_tag PRIMARY KEY(id_tag),
--    CONSTRAINT AK_nb_tag UNIQUE(tag_name)
-- );

-- -- Creation of the table with tags associated with posts
-- CREATE TABLE IF NOT EXISTS nb_post_tag(
--    id_post INT NOT NULL,
--    id_tag INT NOT NULL,
--    CONSTRAINT PK_nb_post_tag PRIMARY KEY(id_post, id_tag),
--    CONSTRAINT FK_nb_nb_post_tag FOREIGN KEY(id_post) REFERENCES nb_post(id_post),
--    CONSTRAINT FK_nb_nb_post_tag FOREIGN KEY(id_tag) REFERENCES nb_tag(id_tag)
-- );

-- Users
DO
$do$
BEGIN
  IF NOT EXISTS (select * from pg_user where usename = 'nb_reader') then
	 CREATE USER nb_reader WITH PASSWORD 'NBlr4--';
	 ELSE
	 DROP OWNED BY nb_reader;
	 DROP USER nb_reader;
	 CREATE USER nb_reader WITH PASSWORD 'NBlr4--';
  END IF;
  IF NOT EXISTS (select * from pg_user where usename = 'nb_writer') then
	 CREATE USER nb_writer WITH PASSWORD 'NBlw2--';
	 ELSE
	 DROP OWNED BY nb_writer;
	 DROP USER nb_writer;
	 CREATE USER nb_writer WITH PASSWORD 'NBlw2--';
  END IF;
  IF NOT EXISTS (select * from pg_user where usename = 'nb_editor') then
	 CREATE USER nb_editor WITH PASSWORD 'NBlrw6--';
	 ELSE
	 DROP OWNED BY nb_editor;
	 DROP USER nb_editor;
	 CREATE USER nb_editor WITH PASSWORD 'NBlrw6--';
  END IF;
END
$do$;

-- Privileges
GRANT SELECT ON ALL TABLES IN SCHEMA newblog TO nb_reader;
GRANT INSERT ON ALL TABLES IN SCHEMA newblog TO nb_writer;
GRANT SELECT, INSERT ON ALL TABLES IN SCHEMA newblog TO nb_editor;
GRANT USAGE ON SCHEMA newblog TO nb_reader;
GRANT USAGE ON SCHEMA newblog TO nb_writer;
GRANT USAGE ON SCHEMA newblog TO nb_editor;

GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA newblog TO nb_reader;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA newblog TO nb_writer;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA newblog TO nb_editor;

COMMIT; -- End of transaction

-- --------------------------------------------------------
