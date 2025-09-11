async function iniciar_sesion() {
    let dni = document.getElementById('dni').value;
    let password = document.getElementById('password').value;

    if (dni == "" || password == "") {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Campos vacíos...',
            footer: '',
            timer: 1500
        });
        return;
    }

    try {
        // Capturar datos del formulario
        const datos = new FormData(frm_login);

        // Enviar datos al controlador de login
        let respuesta = await fetch(base_url_server + 'src/control/Login.php?tipo=iniciar_sesion', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();

        if (json.status) {
            // Preparar datos para iniciar sesión en el cliente
            const formData = new FormData();
            formData.append('session', json.contenido['sesion_id']);
            formData.append('usuario', json.contenido['sesion_usuario']);
            formData.append('nombres_apellidos', json.contenido['sesion_usuario_nom']);
            formData.append('token', json.contenido['sesion_token']);
            // Eliminado: formData.append('id_ies', json.contenido['sesion_ies']);

            // Enviar datos a sesion_cliente.php y esperar la respuesta
            let respuestaSesion = await fetch(base_url + 'src/control/sesion_cliente.php?tipo=iniciar_sesion', {
                method: 'POST',
                mode: 'cors',
                cache: 'no-cache',
                body: formData,
                timer: 1800
                
            });

            location.replace(base_url);
            location.replace(base_url);
        } else {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: json.msg,
                footer: '',
                timer: 1500
            })
        }
        //console.log(respuesta);
    } catch (e) {
        console.log("Error al cargar categorias" + e);
    }
}

// Asignar evento al formulario
if (document.querySelector('#frm_login')) {
    document.querySelector('#frm_login').onsubmit = function(e) {
        e.preventDefault();
        iniciar_sesion();
    };
}
async function cerrar_sesion() {
    let respuesta = await fetch(base_url + 'src/control/sesion_cliente.php?tipo=cerrar_sesion');
    json = await respuesta.json();
    if (json.status) {
        location.replace(base_url);
    }
}
// ---------------------------------------------  CAMBIAR CONTRASEÑA -----------------------------------------------
async function sent_email_password() {
    const datos = new FormData();
    datos.append('sesion', session_session);
    datos.append('token', token_token);
    try {
        let respuesta = await fetch(base_url_server + 'src/control/Usuario.php?tipo=sent_email_password', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

    } catch (error) {

    }
}