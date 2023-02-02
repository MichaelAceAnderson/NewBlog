<?php
	if(!empty($_FILES))
	{
		$nomFichier = $_FILES['file']['name'];
		$tempRep = $_FILES['file']['tmp_name'];
		//$tailleFichier = $_FILES['file']['size'];
		//$typeFichier = $_FILES['file']['type'];
		$error = $_FILES['file']['error'];
		$rep = $_SERVER["DOCUMENT_ROOT"];
		$video = array('.mp4', '.avi', '.mov', '.webm');
		$image = array('.png', '.gif', '.jpg', '.jpeg');
		$extension = strrchr($_FILES['file']['name'], '.');
		
		if($error != 0 || !$tempRep)
		{
			echo 'Erreur: Le fichier n\'a pas pu être uploadé';
			die();
		}
		
		elseif(in_array($extension, $image))
		{
			if(move_uploaded_file($tempRep, $rep.('/admin/media/' . $nomFichier .'')))
			{
				echo '<p style="color: white;"> Chargement du fichier '.$nomFichier.' terminé !</p>';	
				$postfile = fopen("settings/post.txt","a+");
				
				if (fwrite($postfile,"<img src=\"/admin/media/$nomFichier\">\n"))
				{
					echo 'Post ajouté !<br/>Image: ' . $nomFichier . '<br/>';
					fclose($postfile);
				}
				else
				{
					echo '<div class="annonce" style="color: white; background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);">Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</div>';
				}
			}
		}
		
		elseif(in_array($extension, $video))
		{
			if(move_uploaded_file($tempRep, $rep.('/admin/media/' . $nomFichier .'')))
			{
				echo '<p style="color: white;"> Chargement du fichier '.$nomFichier.' terminé !</p>';	
				$postfile = fopen("settings/post.txt","a+");
				
				if (fwrite($postfile,"<video controls=\"\" preload=\"\" src=\"/admin/media/$nomFichier\">\n"))
				{
					echo 'Post ajouté !<br/>Video: ' . $nomFichier . '<br/>';
					fclose($postfile);
				}
				else
				{
					echo '<div class="annonce" style="color: white; background: rgb(212, 0, 0) none repeat scroll 0% 0%; border-left: 10px solid rgb(152, 0, 0);">Une erreur est apparue, vous n\'avez peut être pas mis le CHMOD de vos fichiers en 777 !</div>';
				}
			}
		}

		else 
		{
			echo 'Le type de fichier n\'est pas accépté !<br/>Image: png, gif, jpg, jpeg (Éditez et enregistrez en png avec paint par exemple)<br/>Vidéo: mp4, webm, mov, avi (ouvrez et enregistrez avec Movie Maker en .mp4 par exemple)';
		}
	}
?>