<!-- Page Content -->
<section class="main" id="main">
	<?php
	// Code for future dynamic page update
	// if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'liveUpdate.js')) {
	//     if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'axios.js')) {
	//         echo '<script src="/controller/js/lib/axios.js"></script>';
	//     } else {
	//         echo '<script src="https://unpkg.com/axios/dist/axios.min.js"></script>';
	//     }
	//     echo '<script src="/controller/liveUpdate.js"></script>';
	// } else {
	//     echo '<script>console.error("Dynamic page update script missing!");</script>';
	// }
	?>
	<div class="title outlined">
		<h1>News Feed</h1>
		<hr>
	</div>
	<div class="content">
		<!-- Display a panel for each post -->
		<?php
		// Check if an error has been stored by the controller
		if (Controller::getState()['state'] == STATE_ERROR) {
			// If the controller has stored an error, display it
			echo '<h1 class="notification error">' . Controller::getState()['message'] . '</h1>';
		}
		// Check if a success has been stored by the controller
		if (Controller::getState()['state'] == STATE_SUCCESS) {
			// If the controller has stored a success, display it
			echo '<h1 class="notification success">' . Controller::getState()['message'] . '</h1>';
		}

		$posts = PostController::getAllPosts();
		if ($posts === []) {
			echo "There are no posts to display at the moment!";
		} elseif (!$posts) {
			echo "An error occurred while retrieving the posts!";
		} else {
			foreach ($posts as $post) {
				echo '<div class="post">';
				echo '<h1 class="post-author">' . UserController::getUserNameById($post->id_user_author);
				if (isset($_SESSION['is_mod']) && $_SESSION['is_mod'] === true) {
					echo '<form class="post-delete" action="" method="POST">
					<input type="hidden" value="' . $post->id_post . '" name="fDeletePostId"/>
					<input type="submit" title="Delete" value="🗑️"/>
					</form>';
				}
				echo '</h1>';
				echo '<div class="post-container">';
				echo '<div class="post-title">' . $post->title . '</div>';
				echo '<div class="post-tags">';
				foreach (explode(";", $post->tags) as $tag) {
					echo '#' . $tag . ' ';
				}
				echo '</div>';
				echo '<div class="post-summary">' . $post->summary . '</div>';
				echo '<div class="post-content">';

				// If there is a folder containing the post's videos
				$videoPath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $post->id_post;
				if (file_exists($videoPath) && is_dir($videoPath)) {
					// If the folder exists
					foreach (glob($videoPath . DIRECTORY_SEPARATOR . '*') as $videoFile) {
						// For all files in the folder
						// Display the video with a source path reformatted to be browser-compatible
						echo '<video class="post-media" alt="Post video" preload="auto" controls autoplay muted loop playsinline>
								<source src="' . str_replace(array($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR), array('', '/'), $videoFile) . '">
							</video>';
					}
				}
				// If there is a folder containing the post's images
				$imagePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'blog_data' . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $post->id_post;
				if (file_exists($imagePath) && is_dir($imagePath)) {
					// If the folder exists
					foreach (glob($imagePath . DIRECTORY_SEPARATOR . '*') as $imageFile) {
						// For all files in the folder
						// Display the image with a source path reformatted to be browser-compatible
						echo '<img class="post-media" src="' . str_replace(array($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR), array('', '/'), $imageFile) . '" alt="Post image" decoding="async"/>';
					}
				}

				echo '<p>' . $post->content . '</p>';
				echo '</div>';
				echo '<p class="post-timestamp">' . $post->time_stamp . '</p>';
				echo '</div>';
				echo '</div>';
			}
		}
		?>
	</div>
</section>
<!-- Script to show/hide post content with a button -->
<script src="/js/postDetails.js"></script>