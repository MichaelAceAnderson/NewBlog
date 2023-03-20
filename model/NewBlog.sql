-- Paramétrage initial
CREATE SCHEMA IF NOT EXISTS newblog;
SET SCHEMA 'newblog';
START TRANSACTION;
SET TIME ZONE 'Europe/Paris';


-- Création de la table des utilisateurs
CREATE SEQUENCE IF NOT EXISTS user_seq; -- Compteur incrémenté à chaque nouvel utilisateur
ALTER SEQUENCE user_seq RESTART WITH 1;
CREATE TABLE IF NOT EXISTS nb_user(
   id_user INT NOT NULL DEFAULT NEXTVAL ('user_seq'),
   nickname VARCHAR(32) NOT NULL,
   password TEXT NOT NULL,
   is_mod BOOLEAN,
   CONSTRAINT PK_nb_user PRIMARY KEY(id_user),
   CONSTRAINT AK_nb_user UNIQUE(nickname)
);

-- Création de la table de paramétrage du blog
CREATE TABLE IF NOT EXISTS nb_blog(
   blog_name VARCHAR(32) DEFAULT 'NewBlog',
   background_url TEXT NOT NULL DEFAULT '/common/img/background.jpg',
   description TEXT NOT NULL DEFAULT 'Mon nouveau blog !',
   creation_date TIMESTAMP(0) NOT NULL DEFAULT now(),
   id_user_owner INT NOT NULL,
   CONSTRAINT PK_nb_blog_name PRIMARY KEY(blog_name),
   CONSTRAINT FK_nb_blog_nb_user_owner FOREIGN KEY(id_user_owner) REFERENCES nb_user(id_user)
);

-- Création de la table des posts
CREATE SEQUENCE IF NOT EXISTS post_seq; -- Compteur incrémenté à chaque nouvel utilisateur
ALTER SEQUENCE post_seq RESTART WITH 1;
CREATE TABLE IF NOT EXISTS nb_post(
   id_post INT DEFAULT NEXTVAL ('post_seq'),
   content TEXT NOT NULL,
   time_stamp TIMESTAMP(0) NOT NULL DEFAULT now(),
   id_user_author INT NOT NULL,
   CONSTRAINT PK_nb_post PRIMARY KEY(id_post),
   CONSTRAINT FK_nb_post_nb_user_author FOREIGN KEY(id_user_author) REFERENCES nb_user(id_user)
);

COMMIT;

-- --------------------------------------------------------
-- Utilisateurs
CREATE USER reader WITH PASSWORD 'PGlr4--';
CREATE USER writer WITH PASSWORD 'PGlw2--';
CREATE USER editor WITH PASSWORD 'PGlrw6--';
-- Privilèges
GRANT SELECT ON ALL TABLES IN SCHEMA newblog TO reader;
GRANT INSERT ON ALL TABLES IN SCHEMA newblog TO writer;
GRANT SELECT, INSERT ON ALL TABLES IN SCHEMA newblog TO editor;
GRANT USAGE ON SCHEMA newblog TO reader;
GRANT USAGE ON SCHEMA newblog TO writer;
GRANT USAGE ON SCHEMA newblog TO editor;

GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA newblog TO reader;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA newblog TO writer;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA newblog TO editor;