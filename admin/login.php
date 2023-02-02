<?php include('./includes/head.php'); ?>
<?php include('./includes/header.php'); ?>
<?php	

	$userfile = 'settings/user.txt';
	$passfile = 'settings/pass.txt';
	
	$installcheck = 'settings/installcheck.txt';

if (file_exists($installcheck))
{
	if (isset($_POST['adminco']))
		{
			if (isset($_POST['adminuser']) AND isset($_POST['adminpass']))
			{
				$adminuser = $_POST['adminuser'];
				$adminpass = $_POST['adminpass'];
				
				$getuser = file_get_contents('settings/user.txt');
				$getpass = file_get_contents('settings/pass.txt');
				
				if($adminuser == $getuser AND $adminpass == $getpass)
				{
				header('Location: ./index.php?adminuser=' . $adminuser . '&adminpass=' . $adminpass . '');
				}
				else
				{
					echo '<p>Pseudo ou mot de passe incorrect !</p>
					<form method="post" enctype="multipart/form-data">
					<p>Identifiant:</p><input type="text" name="adminuser" />
					<br/>
					<p>Mot de passe:</p><input type="password" name="adminpass" />
					<br/>
					<input type="submit" class="button" onfocus="this.blur()" value="Connexion" name="adminco" />
					</form>';
				}
			}
		}
	else
	{
		echo '<p>Connectez vous:</p><br/>
		<form method="post" enctype="multipart/form-data">
		<p>Identifiant:</p><input type="text" name="adminuser" />
		<br/>
		<p>Mot de passe:</p><input type="password" name="adminpass" />
		<br/>
		<input type="submit" class="button" onfocus="this.blur()" value="Connexion" name="adminco" />
		</form>';
	}
}
else 
{
	include("install.php");
}
?>
<?php include('./includes/footer.php'); ?>