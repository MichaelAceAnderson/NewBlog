function sheetLoaded(type) {
	switch (type) {
		case 'general': {
			console.log('General stylesheet loaded!');
			break;
		}
		case 'mobile': {
			console.log('Mobile stylesheet loaded!');
			break;
		}
		case 'print': {
			console.log('Print stylesheet loaded!');
			break;
		}
		case 'light': {
			console.log('Light stylesheet loaded!');
			break;
		}
		case 'dark': {
			console.log('Dark stylesheet loaded!');
			break;
		}
		default: {
			console.log('Stylesheet loaded!');
			break;
		}
	}
}

function sheetError(type) {
	switch (type) {
		case 'general': {
			console.log('Error loading general stylesheet!');
			break;
		}
		case 'mobile': {
			console.log('Error loading mobile stylesheet!');
			break;
		}
		case 'print': {
			console.log('Error loading print stylesheet!');
			break;
		}
		case 'light': {
			console.log('Error loading light stylesheet!');
			break;
		}
		case 'dark': {
			console.log('Error loading dark stylesheet!');
			break;
		}
		default: {
			console.log('Error loading stylesheet!');
			break;
		}
	}
}
