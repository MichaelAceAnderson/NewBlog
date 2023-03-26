-- Paramétrage initial
START TRANSACTION;
DROP SCHEMA IF EXISTS newblog CASCADE;
CREATE SCHEMA IF NOT EXISTS newblog;
SET SCHEMA 'newblog';
SET TIME ZONE 'Europe/Paris';


-- Création de la table des utilisateurs
CREATE SEQUENCE IF NOT EXISTS user_seq; -- Compteur incrémenté à chaque nouvel utilisateur
ALTER SEQUENCE user_seq RESTART WITH 1; -- Redémarre le compteur à 1
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
   description TEXT NOT NULL DEFAULT 'Mon nouveau blog !',
   background_url TEXT NOT NULL DEFAULT '/common/img/background.jpg',
   creation_date TIMESTAMP(0) NOT NULL DEFAULT now(),
   id_user_owner INT NOT NULL,
   register_date TIMESTAMP(0) NOT NULL DEFAULT now(),
   CONSTRAINT PK_nb_blog_name PRIMARY KEY(blog_name),
   CONSTRAINT FK_nb_blog_nb_user_owner FOREIGN KEY(id_user_owner) REFERENCES nb_user(id_user)
);

-- Création de la table des posts
CREATE SEQUENCE IF NOT EXISTS post_seq; -- Compteur incrémenté à chaque nouvel utilisateur
ALTER SEQUENCE post_seq RESTART WITH 1; -- Redémarre le compteur à 1
CREATE TABLE IF NOT EXISTS nb_post(
   id_post INT DEFAULT NEXTVAL ('post_seq'),
   content TEXT NOT NULL,
   time_stamp TIMESTAMP(0) NOT NULL DEFAULT now(),
   id_user_author INT NOT NULL,
   CONSTRAINT PK_nb_post PRIMARY KEY(id_post),
   CONSTRAINT FK_nb_post_nb_user_author FOREIGN KEY(id_user_author) REFERENCES nb_user(id_user)
);

-- Utilisateurs
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

-- Privilèges
GRANT SELECT ON ALL TABLES IN SCHEMA newblog TO nb_reader;
GRANT INSERT ON ALL TABLES IN SCHEMA newblog TO nb_writer;
GRANT SELECT, INSERT ON ALL TABLES IN SCHEMA newblog TO nb_editor;
GRANT USAGE ON SCHEMA newblog TO nb_reader;
GRANT USAGE ON SCHEMA newblog TO nb_writer;
GRANT USAGE ON SCHEMA newblog TO nb_editor;

GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA newblog TO nb_reader;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA newblog TO nb_writer;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA newblog TO nb_editor;

COMMIT; -- Fin de la transaction

-- --------------------------------------------------------