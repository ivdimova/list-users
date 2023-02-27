const table = document.getElementById( 'user-details' );
const button = document.getElementById('users-udpate');
button.addEventListener('click', (event) => {
	update_table(table);
});
	
function update_table(table) {
	actionUrl = table.getAttribute('action');
	nonce = table.getAttribute('data-nonce');

	const data = {
		'action': 'api_user_details',
		'nonce': nonce,
	}

	const url = new URL( actionUrl );
	url.search = new URLSearchParams( data ).toString();
	fetch( url, {
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
			'Cache-Control': 'no-cache',
		},
	} )
		.then( response => response.json() )
		.then( response => {
			const tableBody = table.querySelector('tbody');
			let newTableBody = document.createElement('tbody');
			Object.values(response.data).forEach(val => {
				let newRow = document.createElement('tr');
				let newName = document.createElement('td');
				let newId = document.createElement('td');
				let newEmail = document.createElement('td');
				newName.className = "user_name";
				newName.innerHTML = val.fname;
				newId.className = "user_id";
				newId.innerHTML = val.id;
				newEmail.className = "user_email";
				newEmail.innerHTML = val.email;
				newRow.appendChild(newId);
				newRow.appendChild(newName);
				newRow.appendChild(newEmail);
				newTableBody.append(newRow);
			  });	
			  tableBody.replaceWith( newTableBody );
		});
}
