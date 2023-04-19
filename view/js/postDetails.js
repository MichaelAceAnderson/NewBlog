// Au chargement de la page, appeler la fonction "hideAllContents" pour cacher le contenu de tous les posts
window.addEventListener(
	"load",
	() => {
		hideAllContents();
	}
);

// Cacher le contenu de tous les posts
function hideAllContents() {
	// Récupérer tous les éléments "contenu" de de chaque post
	const postsContent = document.getElementsByClassName("post-content");

	// Pour tous les éléments "contenu" récupérés, rajouter une classe "short" pour les réduire
	for (let i = 0; i < postsContent.length; i++) {
		postsContent[i].classList.add("short");
	}
}

// Fonction permettant d'insérer un élément après un autre élément
function insertAfter(referenceNode, newNode) {
	referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

// Dans tous les éléments sommaires de chaque post, ajouter un bouton "Afficher les détails" en dessous de l'élément de classe "post-summary"
const postSummaries = document.getElementsByClassName("post-summary");
for (let currentPost = 0; currentPost < postSummaries.length; currentPost++) {
	// Récupérer le sommaire du post actuel
	const postSummary = postSummaries[currentPost];
	// Récupérer le contenu du post actuel
	const postContent = postSummary.nextElementSibling;
	// Créer un bouton pour afficher/cacher le contenu du post
	const toggleButton = document.createElement("button");
	toggleButton.innerHTML = "Afficher les détails";
	toggleButton.classList.add("post-content-toggle");
	// Insérer le bouton en dessous du sommaire du post
	insertAfter(postSummary, toggleButton);
	// Au clic du bouton, appeler la fonction "toggleContent" pour afficher/cacher le contenu du post
	// En passant en paramètre le bouton source dont il faut changer le texte et l'élément contenu du post
	toggleButton.addEventListener(
		"click",
		(thisEvent) => {
			toggleContent(thisEvent.currentTarget, postContent);
		}
	);
}

// Fonction pour afficher/cacher le contenu d'un post
function toggleContent(sourceButton, postContent) {
	// Si l'élément de classe "post-content" a la classe "short"
	if (postContent.classList.contains("short")) {
		// Enlever la classe hidden pour dérouler l'élément
		postContent.classList.remove("short");
		// Changer le texte du bouton
		sourceButton.innerHTML = "Cacher les détails";
	} else {
		// Si l'élément de classe "post-content" n'a pas la classe "short"
		// Rajouter la classe short pour cacher l'élément
		postContent.classList.add("short");
		// Changer le texte du bouton
		sourceButton.innerHTML = "Afficher les détails";
	}
}