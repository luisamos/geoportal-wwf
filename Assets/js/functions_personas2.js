// Función genérica para realizar solicitudes POST utilizando fetch
async function postData(url, formData) {
  try {
    const response = await fetch(url, {
      method: 'POST',
      body: formData,
    });

    if (!response.ok) {
      throw new Error('Error en la solicitud.');
    }

    return response.json();
  } catch (error) {
    throw new Error('Error en la solicitud.');
  }
}

// Función para validar si todos los campos están llenos
function validateFields(...fields) {
  return fields.every((field) => {
    // Si el campo no tiene definida la propiedad 'value' o si está vacío pero no tenía un valor cargado inicialmente, lo consideramos inválido
    if (!field.value || (field.value.trim() === '' && !field.dataset.initialValue)) {
      return false;
    }
    return true;
  });
}




// Función para mostrar un mensaje de notificación
function showToast(message) {
  new Toast(message);
}

document.addEventListener('DOMContentLoaded', function () {
  if (document.querySelector("#formPersonas")) {
    const formPersonas = document.querySelector("#formPersonas");

    formPersonas.onsubmit = async function (e) {
      e.preventDefault();
      const strNum_documento = document.querySelector('#num_documento').value;
      const strNombres = document.querySelector('#nombres').value;
      const strApellidos = document.querySelector('#apellidos').value;
      const strCelular = document.querySelector('#celular').value;
      const strEmail = document.querySelector('#email').value;

      if (!validateFields(strNum_documento, strNombres, strApellidos, strCelular, strEmail)) {
        showToast("Todos los campos son obligatorios.");
        return false;
      }

      try {
        const ajaxUrl = `${base_url}/Personas/setPersona`;
        const formData = new FormData(formPersonas);
        const objData = await postData(ajaxUrl, formData);

        if (objData.status) {
          formPersonas.reset();
          Fancybox.close();
          showToast(objData.msg);
          setTimeout(function () {
            window.location.reload();
          }, 2000);
        } else {
          showToast("Error: " + objData.msg);
        }
      } catch (error) {
        showToast("Error en la solicitud.");
      }
    };
  }

  if (document.querySelector("#formPersonasDel")) {
    const formPersonasDel = document.querySelector("#formPersonasDel");

    formPersonasDel.onsubmit = async function (e) {
      e.preventDefault();

      try {
        const ajaxUrl = `${base_url}/Personas/delPersona`;
        const formData = new FormData(formPersonasDel);
        const objData = await postData(ajaxUrl, formData);

        if (objData.status) {
          formPersonasDel.reset();
          Fancybox.close();
          showToast(objData.msg);
          setTimeout(function () {
            window.location.reload();
          }, 2000);
        } else {
          showToast("Error: " + objData.msg);
        }
      } catch (error) {
        showToast("Error en la solicitud.");
      }
    };
  }
});


async function fntEditInfo() {
  const check = document.querySelector('input[name="ckPersonas"]:checked');
  if (!check) {
    new Toast('Seleccione una Persona');
    return;
  }

  document.getElementById('id_persona').value = check.dataset.id;
  const id_persona = check.dataset.id;
  document.querySelector('#titleModal').innerHTML = "Actualizar Persona";
  isNewPersona = false;

  try {
    const ajaxUrl = `${base_url}/Personas/getPersona/${id_persona}`;
    const response = await fetch(ajaxUrl);

    if (!response.ok) {
      throw new Error('Error en la solicitud.');
    }

    const objData = await response.json();

    if (objData.status) {
      document.querySelector("#id_persona").value = objData.data.id_persona;
      document.querySelector("#num_documento").value = objData.data.person_num_documento;
      document.querySelector("#nombres").value = objData.data.person_nombres;
      document.querySelector("#apellidos").value = objData.data.person_apellidos;
      document.querySelector("#celular").value = objData.data.person_celular;
      document.querySelector("#email").value = objData.data.person_email;
      document.querySelector("#estado").value = objData.data.person_estado;

      // Guardar el valor inicial de cada campo en el atributo data-initial-value
      document.querySelector("#num_documento").dataset.initialValue = objData.data.person_num_documento;
      document.querySelector("#nombres").dataset.initialValue = objData.data.person_nombres;
      document.querySelector("#apellidos").dataset.initialValue = objData.data.person_apellidos;
      document.querySelector("#celular").dataset.initialValue = objData.data.person_celular;
      document.querySelector("#email").dataset.initialValue = objData.data.person_email;

      new Fancybox([{ src: "#modalFormPersonas" }]);
    } else {
      new Toast("Error: " + objData.msg);
    }
  } catch (error) {
    new Toast("Error en la solicitud.");
  }
}



function openModalDel() {
  const check = document.querySelector('input[name="ckPersonas"]:checked');
  if (!check) {
    new Toast('Seleccione una Persona');
    return;
  }

  document.getElementById('id_persona_del').value = check.dataset.id;
  new Fancybox([{ src: "#modalFormPersonasDel" }]);
}

function openModal() {
  rowTable = "";
  document.querySelector('#id_persona').value = "";
  document.querySelector('#titleModal').innerHTML = "Nueva Persona";
  document.querySelector("#formPersonas").reset();
}