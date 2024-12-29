// On page load, call the "hideAllContents" function to hide the content of all posts
window.addEventListener(
	"load",
	() => {
		hideAllContents();
	}
);

// Hide the content of all posts
function hideAllContents() {
	// Get all "content" elements of each post
	const postsContent = document.getElementsByClassName("post-content");

	// For all retrieved "content" elements, add a "short" class to reduce them
	for (let i = 0; i < postsContent.length; i++) {
		postsContent[i].classList.add("short");
	}
}

// Function to insert an element after another element
function insertAfter(referenceNode, newNode) {
	referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

// In all summary elements of each post, add a "Show details" button below the "post-summary" element
const postSummaries = document.getElementsByClassName("post-summary");
for (let currentPost = 0; currentPost < postSummaries.length; currentPost++) {
	// Get the summary of the current post
	const postSummary = postSummaries[currentPost];
	// Get the content of the current post
	const postContent = postSummary.nextElementSibling;
	// Create a button to show/hide the content of the post
	const toggleButton = document.createElement("button");
	toggleButton.innerHTML = "Show details";
	toggleButton.classList.add("post-content-toggle");
	// Insert the button below the summary of the post
	insertAfter(postSummary, toggleButton);
	// On button click, call the "toggleContent" function to show/hide the content of the post
	// Passing the source button whose text needs to be changed and the post content element
	toggleButton.addEventListener(
		"click",
		(thisEvent) => {
			toggleContent(thisEvent.currentTarget, postContent);
		}
	);
}

// Function to show/hide the content of a post
function toggleContent(sourceButton, postContent) {
	// If the "post-content" element has the "short" class
	if (postContent.classList.contains("short")) {
		// Remove the "short" class to expand the element
		postContent.classList.remove("short");
		// Change the button text
		sourceButton.innerHTML = "Hide details";
	} else {
		// If the "post-content" element does not have the "short" class
		// Add the "short" class to hide the element
		postContent.classList.add("short");
		// Change the button text
		sourceButton.innerHTML = "Show details";
	}
}