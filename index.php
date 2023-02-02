<?php
$server = $_SERVER['DOCUMENT_ROOT'];
$getEtape = file_get_contents($server.'/admin/settings/installcheck.txt');
	if ($getEtape == "ok")
	{
		include('admin/includes/head.php');
		include('admin/includes/header.php');
		

		$descfile = 'admin/settings/description.txt';
		$getdesc = file_get_contents($descfile);
		
		if ($getdesc == "")
		{
			echo '<br/>';
		}
		else
		{
			$desc=file($descfile);

			echo '<div class="annonce">';
					
			for( $i = 0 ; $i < count($desc) ; $i++ )
					{
						echo $desc[$i];
					}
					
			echo '</div>';
		}

			$postfile = 'admin/settings/post.txt';
			$post=file($postfile);
			$post=array_reverse($post);
			
			for( $i = 0 ; $i < count($post) ; $i++ )
			{
				echo '<div class="panel panel-primary" style="font-family: \'Arial\', sans-serif">
				<div class="panel-heading"><center></center></div>
				<div class="panel-body"><p>' . $post[$i] . '</p></div>
				</div>';
			}
	include('admin/includes/footer.php');
	}
else
{
include('admin/install.php');
}
?>