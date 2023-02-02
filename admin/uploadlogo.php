<?php
	if(!empty($_FILES))
	{
		$nomLogo = $_FILES['logo']['name'];
		$tempLogo = $_FILES['logo']['tmp_name'];
		//$tailleFichier = $_FILES['logo']['size'];
		//$typeFichier = $_FILES['logo']['type'];
		$errorLogo = $_FILES['logo']['error'];
		$server = $_SERVER["DOCUMENT_ROOT"];
		$logoext = array('.png', '.gif', '.jpg', '.jpeg', '.ico');
		$extensionLogo = strrchr($_FILES['logo']['name'], '.');

		if($errorLogo != 0 || !$tempLogo)
		{
			echo 'Erreur: Le Logo n\'a pas pu être uploadé';
			die();
		}
		
		elseif(in_array($extensionLogo, $logoext))
		{
			if(move_uploaded_file($tempLogo, $server.('/admin/css/' . $nomLogo . '')))
			{
				$getlogo = file_get_contents($server.'/admin/settings/logo.txt');
				if($getlogo != "")
				{
					unlink($server.$getlogo);
				}
				echo '<p style="color: white;"> Chargement du fichier ' . $nomLogo . ' terminé !</p>';	
				$openlogofile = fopen("settings/logo.txt","w+");
				
				if (fwrite($openlogofile,"/admin/css/$nomLogo"))
				{
					echo 'Logo ajouté !<br/>Image: ' . $nomLogo . '<br/><a href="" class="button">Actualiser</a><br/>';
					fclose($openlogofile);
				}
				else
				{
					echo '<div class="annonce" style="color: white; background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);">Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</div>';
				}
			}
		}

		else 
		{
			echo 'Le type de fichier n\'est pas accépté !<br/>Logo: png, gif, jpg, jpeg, ico (Éditez et enregistrez en png avec paint par exemple)';
		}
	}
	else
	{
		$server = $_SERVER["DOCUMENT_ROOT"];
		$openlogofile = fopen("settings/logo.txt","w+");
			
		if (ftruncate($openlogofile, 0))
		{
			echo 'Logo supprimé !<br/><a href="" class="button">Actualiser</a><br/>';
			fclose($openlogofile);
		}
		else
		{
			echo '<div class="annonce" style="color: white; background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);">Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</div>';
		}
	}
?>