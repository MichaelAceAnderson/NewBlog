-- Paramétrage initial
START TRANSACTION;
USE newblog;

-- Users
CREATE USER IF NOT EXISTS 'nb_reader'@'%' IDENTIFIED BY 'NBlr4--';
CREATE USER IF NOT EXISTS 'nb_writer'@'%' IDENTIFIED BY 'NBlw2--';
CREATE USER IF NOT EXISTS 'nb_editor'@'%' IDENTIFIED BY 'NBlrw6--';

-- Privileges
GRANT SELECT ON newblog.* TO 'nb_reader'@'%';
GRANT INSERT ON newblog.* TO 'nb_writer'@'%';
GRANT SELECT, INSERT ON newblog.* TO 'nb_editor'@'%';

COMMIT; -- End of transaction
