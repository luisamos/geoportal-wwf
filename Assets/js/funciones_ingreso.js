$('.login-content [data-toggle="flip"]').click(function () {
	$('.login-box').toggleClass('flipped');
	return false;
});

document.addEventListener('DOMContentLoaded', function () {
	if (document.querySelector("#formLogin")) {
		let formLogin = document.querySelector("#formLogin");

		formLogin.onsubmit = function (e) {
			e.preventDefault();

			let strEmail = document.querySelector('#txtUsuario').value.trim();
			let strPassword = document.querySelector('#txtClave').value.trim();

			if (strEmail === "" || strPassword === "") {
				swal("Por favor", "Escribe usuario y contraseña.", "error");
				return false;
			}

			let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
			let ajaxUrl = base_url + 'Ingreso/loginUser';
			let formData = new FormData(formLogin);
			request.open("POST", ajaxUrl, true);
			request.send(formData);

			request.onreadystatechange = function () {
				if (request.readyState !== 4) return;

				if (request.status === 200) {
					let objData = JSON.parse(request.responseText);

					if (objData.status === true) {
						// ✅ Login exitoso
						window.location.reload(false);
					} else {
						// ❌ Login fallido
						new Toast("Error: " + objData.msg);
						document.querySelector('#txtClave').value = "";

						// ⛔ Si fue bloqueo por 3 intentos, alerta y desactiva
						if (objData.msg.includes("bloqueado")) {
							swal("Usuario bloqueado", "Ha alcanzado el número máximo de intentos. Contacte al administrador.", "error");

							// Desactivar formulario opcionalmente
							document.querySelector('#txtUsuario').disabled = true;
							document.querySelector('#txtClave').disabled = true;
							formLogin.querySelector('button[type="submit"]').disabled = true;
						}
					}
				} else {
					new Toast("Error de red al intentar iniciar sesión.");
				}
			}
		}
	}
}, false);