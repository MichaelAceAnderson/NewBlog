	<ul class="nav">
	    <center>
	        <?php
			if (isset($blogname)) {
				echo '<a class="titre" href="/">' . $blogname . '</a>';
			} else {
				echo '<a class="titre" href="/">NewBlog</a>';
			}
			?>
	    </center>
	    <form action="/view/pages/admin-panel.php" style="margin: 0;">
	        <button onfocus="this.blur();" class="admin">Panel Admin</button>
	    </form>
	</ul>
	<div class="center">