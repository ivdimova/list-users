const table = document.getElementById( 'user-details' );
const button = document.getElementById('users-udpate');
const title = document.getElementById('users-title');
const attributes = window.listUsers;

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
			title.innerHTML = attributes.title;
			const tableBody = table.querySelector('tbody');
			let newTableBody = document.createElement('tbody');
			Object.values(response.data).forEach(val => {
				let newRow = document.createElement('tr');
				if ( attributes.idChecked !== true ) {
					let newId = document.createElement('td');
					newId.className = "user_id";
					newId.innerHTML = val.id;
					newRow.appendChild(newId);
				}

				if ( attributes.nameChecked !== true ) {
					let newName = document.createElement('td');
					newName.className = "user_name";
					newName.innerHTML = val.fname;
					newRow.appendChild(newName);
				}

				if ( attributes.emailChecked !== true ) {
					let newEmail = document.createElement('td');
					newEmail.className = "user_email";
					newEmail.innerHTML = val.email;
					newRow.appendChild(newEmail);
				}
				newTableBody.append(newRow);
			  });	
			  tableBody.replaceWith( newTableBody );
		});
}
update_table(table);