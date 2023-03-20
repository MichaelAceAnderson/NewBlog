<?php
class BlogController
{
    /* MÉTHODES */

    /* Créations */
    // Installer le blog
    public static function installBlog()
    {
        Blog::install();
    }

    /* Modifications */
    // Définir le nom du blog
    public static function setBlogName($newBlogName): bool
    {
        $result = Blog::updateBlogName($newBlogName);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }
    // Changer l'image image de fond 
    public static function setBackgroundURL($newBackgroundURL): bool
    {
        $result = Blog::updateBackgroundURL($newBackgroundURL);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }
    // Changer la description 
    public static function setDescription($newDescription): bool
    {
        $result = Blog::updateDescription($newDescription);
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result;
        }
    }

    /* Récupérations */
    // Récupérer le nom du blog
    public static function getBlogName(): string | false
    {
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result[0]->blog_name ?? false;
        }
    }
    // Récupérer l'URL de l'image de fond du blog
    public static function getBackgroundURL(): string | false
    {
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result[0]->background_url ?? false;
        }
    }
    // Récupérer la description du blog
    public static function getBlogDescription(): string | false
    {
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result[0]->description ?? false;
        }
    }
    // Récupérer la date de création du blog
    public static function getCreationDate(): string | false
    {
        $result = Blog::selectBlog();
        if ($result instanceof PDOException) {
            Model::printLog(Model::getError($result));
            return false;
        } else {
            return $result[0]['creation_date'] ?? false;
        }
    }
}
// if(isset($_POST['fInstall'])) {
//     BlogController::setBlogName($_POST['fBlogName']);
//     BlogController::setDescription($_POST['fDescription']);
// }