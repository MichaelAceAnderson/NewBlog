<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Installation NewBlog CMS</title>
        <link type="image/x-icon" rel="shortcut icon" href="/admin/css/newblog.png">
		<meta name="viewport" content="width=device-width, initial-scale=0.5">
		<meta charset="utf-8" />
		
        <!-- Style -->

        <link href="/admin/css/custom.css" rel="stylesheet">
        <!-- 
		<link href="/admin/css/bootstrap.css" rel="stylesheet">
        <link href="/admin/css/font-awesome.css" rel="stylesheet">
		-->
    </head>
	<body style="background-image: url(/admin/css/background.jpg)">
	
<?php
$server = $_SERVER['DOCUMENT_ROOT'];
$getEtape = file_get_contents($server.'/admin/settings/installcheck.txt');

if ($getEtape == "ok")
{
	echo '<div class="annonce">Votre CMS est déjà installé !</div>';
}
elseif ($getEtape == "")
{
	$installEtape = fopen($server.'/admin/settings/installcheck.txt',"w+");

	if (fwrite($installEtape,"1"))
	{
	echo '<meta http-equiv="refresh" content="1; url="">';
	echo '<center style="padding-top: 100px; font-size: 60px;"><h1>Chargement...</h1></center>';
	}
	else 
	{
		echo '<div class="annonce" style="padding-top: 100px; font-size: 60px;; background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);"><p>Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</p></div>';
	}
}
else 
{

	echo '<div class="annonce" style="background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);">Votre CMS n\'est pas installé !</div>';
	echo '<div class="annonce" style="font-size: 25px;">Etape ' . $getEtape . '/5</div>';
		//Définir les identifiants (Form)
				
				if (isset($_POST['idconfig']))

				{

					if (isset($_POST['adduser']) AND isset($_POST['addpass']))

					{

						$adduser = $_POST['adduser'];

						$addpass = $_POST['addpass'];

						$installEtape = fopen($server.'/admin/settings/installcheck.txt',"w+");

						$userfile = fopen($server.'/admin/settings/user.txt',"w+");

						$passfile = fopen($server.'/admin/settings/pass.txt',"w+");
						
						if($adduser != "" AND $addpass != "")
						{
							if (fwrite($userfile,$adduser) AND fwrite($passfile,$addpass) AND fwrite($installEtape,"2"))

							{

							echo '<div class="annonce">Identifiant et mot de passe édités !<br/>ID: ' . $adduser . '<br/> MDP: ' . $addpass . '<br/></div><br/><center><p>Cliquez ici si vous n\'êtes pas redirigés vers la prochaine étape !</p><a class="button" style="margin: 20px;" href="">Actualiser</a></center>';
							echo '<center><p>Chargement...</p></center>';

							echo '<meta http-equiv="refresh" content="2; url="">';
							fclose($userfile);

							fclose($passfile);

							}
							else 
							{
								echo '<div class="annonce"><p>Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</p></div>';
							}
						}
						else
						{
							echo '<div class="annonce" style="background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);">Les identifiants ne peuvent pas être vides !</div>';
						}

					}

				}

	//Ajouter un post (Form)
				
				if (isset($_POST['vfirstpost']))

				{

					if (isset($_POST['firstpost']))

					{
						
						$postfile = fopen($server.'/admin/settings/post.txt',"a+");
						
						$installEtape = fopen($server.'/admin/settings/installcheck.txt',"w+");

						$firstpost = $_POST['firstpost'];



						if ($firstpost != "")

						{

							if (fwrite($postfile,"$firstpost\n") AND fwrite($installEtape,"3"))

							{

								echo '<div class="annonce">Post ajouté !<br/>Texte: ' . $firstpost . '</div><br/><center><p>Cliquez ici si vous n\'êtes pas redirigés vers la prochaine étape !</p><a class="button" style="margin: 20px;" href="">Actualiser</a></center>';
								echo '<center><p>Chargement...</p></center>';
								echo '<meta http-equiv="refresh" content="3; url="">';

								fclose($postfile);
								

							}
							else 
							{
								echo '<div class="annonce" style="background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);"><p>Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</p></div>';
							}

						}

						else

						{

							echo '<div class="annonce" style="background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);"><p>Un post ne peut pas être vide !</p></div>';
						}

					}

				}
				
	//Définir le nom du blog (Form)		

				if (isset($_POST['vnblog']))

				{

					if (isset($_POST['nblog']))

					{

						$nblog = $_POST['nblog'];
			
						$namefile = fopen($server.'/admin/settings/name.txt',"w+");
						
						$installEtape = fopen($server.'/admin/settings/installcheck.txt',"w+");

						if ($nblog != "")
						{
							if (fwrite($namefile,"$nblog") AND fwrite($installEtape,"4"))

							{

									echo '<div class="annonce">Nom changé !<br/>Nom: ' . $nblog . '</div><br/><center><p>Cliquez ici si vous n\'êtes pas redirigés vers la prochaine étape !</p><a class="button" style="margin: 20px;" href="">Actualiser</a></center>';
									echo '<center><p>Chargement...</p></center>';
									echo '<meta http-equiv="refresh" content="3; url="">';

									fclose($namefile);
							}
							else 
							{
								echo '<div class="annonce" style="background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);"><p>Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</p></div>';
							}
						}
						
						else					
						{

							echo '<div class="annonce" style="background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);"><p>Le nom ne peut pas être vide !</p></div>';

						}

					}

				}

	//Définir la description (Form)
				
				if (isset($_POST['vdescblog']))

				{

					if (isset($_POST['descblog']))

					{
						
						$descfile = fopen($server.'/admin/settings/description.txt',"w+");
						
						$installEtape = fopen($server.'/admin/settings/installcheck.txt',"w+");

						$descblog = $_POST['descblog'];

						if (fwrite($descfile,"$descblog") AND fwrite($installEtape,"5"))
						{

						echo '<div class="annonce">Description changée !<br/>Texte: ' . $descblog . '</div><br/><center><p>Cliquez ici si vous n\'êtes pas redirigés vers la prochaine étape !</p><a class="button" style="margin: 20px;" href="">Actualiser</a></center>';
						echo '<center><p>Chargement...</p></center>';
						echo '<meta http-equiv="refresh" content="3; url="">';
						fclose($descfile);
						
						}
						
						else 
							
						{
							echo '<div class="annonce" style="background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);"><p>Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</p></div>';
						}
						
					}

				}
				
				if (isset($_POST['vall']))
				{
					$installEtape = fopen($server.'/admin/settings/installcheck.txt',"w+");
					
					if (fwrite($installEtape,"ok"))
					{
					
					echo '<div class="annonce"><p>L\'installation est terminée !<br/>Patientez 3 secondes...</p></div>';
					fclose($installEtape);
					echo '<meta http-equiv="refresh" content="3; url="">';
					
					$version = file_get_contents($server.'/admin/settings/version.txt');
					$URL = "http://".$_SERVER['HTTP_HOST']; 
					$SendInstall = file_get_contents("http://xdev.livehost.fr/creations/web/newblog/bloginstalled.php?url=$URL&version=$version");
					
					}
					
					else 
						
					{
						echo '<div class="annonce" style="background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);"><p>Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</p></div>';
					}
				}				
				
				
				
				
				
				
				
	//Définir les identifiants (Echo)
	if($getEtape == "1")
	{
				echo '<div class="panel panel-primary" style="">

				<div class="panel-heading"><center>Configurer les identifiants</center></div>

				<div class="panel-body">

				<form method="post" enctype="multipart/form-data">

				<p>Entrez les identifiants:</p><br/>

				<p>Identifiant:</p><input type="text" autocomplete="off" name="adduser" />

				<br/>

				<p>Mot de passe:</p><input type="password" autocomplete="off" name="addpass" />

				<br/>

				<input class="button" type="submit" onfocus="this.blur();" value="Envoyer" name="idconfig" />

				</form></div></div>';
	}
	//Ajouter un post écrit (Echo)
	elseif($getEtape == "2")
	{
			
				echo '<div class="panel panel-primary" style="">

				<div class="panel-heading"><center>Ajouter un premier post écrit</center></div>

				<div class="panel-body">

				<form method="post" enctype="multipart/form-data">

				<p>Ajouter un post:<br/><b>Attention</b>: <i>Les post ne sont pas modifiables une fois envoyés ! (vous pouvez tout de même modifier la source sur votre FTP a l\'adresse <b>/admin/settings/post.txt</b>)</i></p><br/>

				<input type="text" autocomplete="off" name="firstpost" />

				<br/>

				<input class="button" type="submit" onfocus="this.blur();"  value="Envoyer" name="vfirstpost" />
				
				<br/>
				</form>
				</div>
				</div>';
	}
	//Définir le nom du blog (Echo)
	elseif($getEtape == "3")
	{
			
				echo '<div class="panel panel-primary" style="">

				<div class="panel-heading"><center>Définir le nom du blog</center></div>

				<div class="panel-body">

				<form method="post" enctype="multipart/form-data">

				<p>Définir le nom du blog:</p><br/>

				<input type="text" autocomplete="off" name="nblog" />

				<br/>

				<input class="button" type="submit" onfocus="this.blur();"  value="Envoyer" name="vnblog" /><br/></form></div></div>';
	}
	//Définir la description du blog (Echo)
	elseif($getEtape == "4")
	{
			
				echo '<div class="panel panel-primary" style="">

				<div class="panel-heading"><center>Changer la description du blog</center></div>

				<div class="panel-body">

				<form method="post" enctype="multipart/form-data">

				<p>Définir la description du blog:</p><br/>

				<textarea style="width: 50%;" name="descblog"></textarea>

				<br/>

				<input class="button" type="submit" onfocus="this.blur();"  value="Envoyer" name="vdescblog" /><br/></form></div></div>';
	}
	//Terminer l'installation (Echo)
	elseif($getEtape == "5")
	{
			
					echo '<center><form method="post" enctype="multipart/form-data">
					<p>Cliquez ici si vous avez terminé l\'installation</p><br/>
					<input class="button" type="submit" onfocus="this.blur();"  value="Terminer l\'installation" name="vall" /><br/></form></center>';
	}
}
?>
<center>
    <iframe width="50%" height="350px" src="" frameborder="0" allowFullScreen="">Bientôt » Vidéo d'installation du CMS !</iframe>
</center>