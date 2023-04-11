function sheetLoaded(type) {
	switch (type) {
		case "general": {
			console.log("Feuille de style générale chargée !");
			break;
		}
		case "mobile": {
			console.log("Feuille de style mobile chargée !");
			break;
		}
		case "print": {
			console.log("Feuille de style d'impression chargée !");
			break;
		}
		case "light": {
			console.log("Feuille de style claire chargée !");
			break;
		}
		case "dark": {
			console.log("Feuille de style sombre chargée !");
			break;
		}
		default: {
			console.log("Feuille de style chargée !");
			break;
		}
	}
}

function sheetError(type) {
	switch (type) {
		case "general": {
			console.log(
				"Erreur lors du chargement de la feuille de style générale !"
			);
			break;
		}
		case "mobile": {
			console.log(
				"Erreur lors du chargement de la feuille de style mobile !"
			);
			break;
		}
		case "print": {
			console.log(
				"Erreur lors du chargement de la feuille de style d'impression !"
			);
			break;
		}
		case "light": {
			console.log(
				"Erreur lors du chargement de la feuille de style claire !"
			);
			break;
		}
		case "dark": {
			console.log(
				"Erreur lors du chargement de la feuille de style sombre !"
			);
			break;
		}
		default: {
			console.log("Erreur lors du chargement de la feuille de style !");
			break;
		}
	}
}
