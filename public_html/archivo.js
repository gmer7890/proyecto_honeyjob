// Listener para el botón de validar y mostrar el modal
document.getElementById("validarBtn").addEventListener("click", function () {
    if (validarFormulario()) {
        mostrarModalRegistro();
    }
});

// Función para validar el formulario antes de agregar un registro
function validarFormulario() {
    let formularioValido = true;
    let campos = ["nombre", "email", "telefono", "comentarios", "pais"];

    campos.forEach((campo) => {
        let input = document.getElementById(campo);
        let errorMensaje = document.getElementById(`error-${campo}`);
        let esValido = input.value.trim() !== "";

        // Validaciones específicas para email y teléfono
        if (campo === "email" && !/^[^\s@]+@[^\s@]+$/.test(input.value)) {
            esValido = false;
            errorMensaje.textContent = "Ingresa un correo válido";
        }

        if (campo === "telefono" && !/^\d{10,12}$/.test(input.value)) {
            esValido = false;
            errorMensaje.textContent = "El teléfono debe tener entre 10 y 12 números";
        }

        // Aplicar clases para mostrar errores o éxito en los campos
        input.classList.toggle("error", !esValido);
        input.classList.toggle("valid", esValido);
        errorMensaje.style.display = esValido ? "none" : "block";

        formularioValido = formularioValido && esValido;
    });

    return formularioValido;
}

// Función para limitar un texto a 32 caracteres y agregar "..." (función estética)
function limitarTexto(texto, limite = 32) {
    return texto.length > limite ? texto.substring(0, limite) + "..." : texto;
}

// Sugerencia automática de correo electrónico
document.getElementById("email").addEventListener("input", function () {
    let emailInput = this.value.trim();
    let sugerencia = document.getElementById("sugerenciaEmail");

    if (emailInput !== "" && !emailInput.includes("@")) {
        sugerencia.textContent = emailInput + "@gmail.com";
        sugerencia.style.display = "block";
    } else {
        sugerencia.style.display = "none";
    }
});

// Evento para autocompletar el email al hacer clic en la sugerencia
document.getElementById("sugerenciaEmail").addEventListener("click", function () {
    let emailInput = document.getElementById("email");
    emailInput.value = this.textContent;
    this.style.display = "none";
});

// Ajustar la altura de la textarea si el comentario tiene más de 25 caracteres
document.getElementById("comentarios").addEventListener("input", function () {
    if (this.value.length > 25) {
        this.style.height = "100px"; // Aumenta la altura de la textarea
    } else {
        this.style.height = "50px"; // Mantiene la altura normal
    }
});

// Función para obtener los intereses seleccionados en el formulario
function obtenerInteresesSeleccionados() {
    return (
        Array.from(document.querySelectorAll('input[name="intereses"]:checked'))
            .map((el) => el.value)
            .join(", ") || "No especificado"
    );
}

// Función para mostrar el modal solo si se ingresó información correctamente
function mostrarModalRegistro() {
    let nombre = document.getElementById("nombre").value.trim();
    let email = document.getElementById("email").value.trim();
    let telefono = document.getElementById("telefono").value.trim();
    let comentarios = document.getElementById("comentarios").value.trim();
    let pais = document.getElementById("pais").value.trim();
    let intereses = obtenerInteresesSeleccionados();

    // Si los campos están vacíos, no se muestra el modal
    if (!nombre || !email || !telefono || !comentarios || !pais) {
        return;
    }

    let modal = document.getElementById("modalRegistro");
    let preview = document.getElementById("registroPreview");

    // Crear contenido del modal con los datos del usuario, limitando a 32 caracteres
    preview.innerHTML = `
        <p><strong>Nombre:</strong> ${limitarTexto(nombre)}</p>
        <p><strong>Email:</strong> ${limitarTexto(email)}</p>
        <p><strong>Teléfono:</strong> ${telefono}</p>
        <p><strong>Comentarios:</strong> ${limitarTexto(comentarios)}</p>
        <p><strong>País:</strong> ${pais}</p>
        <p><strong>Intereses:</strong> ${limitarTexto(intereses)}</p>
    `;

    // Mostrar el modal
    modal.style.display = "flex";
}

// Evento para aceptar el registro y moverlo debajo del formulario
document.getElementById("aceptarRegistro").addEventListener("click", function () {
    moverRegistroAlCatalogo();
    document.getElementById("modalRegistro").style.display = "none"; // Ocultar modal
});

// Función para mover el registro del modal al catálogo
function moverRegistroAlCatalogo() {
    let catalogo = document.getElementById("catalogoRegistros");
    catalogo.classList.remove("hidden"); // Mostrar el catálogo si está oculto

    let nuevoRegistro = document.createElement("div");
    nuevoRegistro.classList.add("registro");

    // Obtener los datos del formulario
    let nombre = document.getElementById("nombre").value;
    let email = document.getElementById("email").value;
    let telefono = document.getElementById("telefono").value;
    let comentarios = document.getElementById("comentarios").value;
    let pais = document.getElementById("pais").value;
    let intereses = obtenerInteresesSeleccionados();

    // Crear estructura del nuevo registro con textos limitados
    nuevoRegistro.innerHTML = `
        <p><strong>Nombre:</strong> ${limitarTexto(nombre)}</p>
        <p><strong>Email:</strong> ${limitarTexto(email)}</p>
        <p><strong>Teléfono:</strong> ${telefono}</p>
        <p><strong>Comentarios:</strong> ${limitarTexto(comentarios)}</p>
        <p><strong>País:</strong> ${pais}</p>
        <p><strong>Intereses:</strong> ${limitarTexto(intereses)}</p>
        <button class="eliminarBtn">Eliminar</button>
    `;

    // Agregar el registro al catálogo
    catalogo.appendChild(nuevoRegistro);

    // Guardar el registro en cookies
    guardarRegistroEnCookies({ nombre, email, telefono, comentarios, pais, intereses });

    // Limpiar el formulario después de agregar el registro
    limpiarFormulario();
}

// Función para guardar un registro en cookies
function guardarRegistroEnCookies(registro) {
    let registros = obtenerRegistrosDeCookies();
    registros.push(registro); // Agregar el nuevo registro
    document.cookie = "registros=" + JSON.stringify(registros) + "; path=/"; // Guardar en cookies
}

// Función para obtener los registros almacenados en cookies
function obtenerRegistrosDeCookies() {
    let cookies = document.cookie.split("; ");
    let registroCookie = cookies.find((cookie) => cookie.startsWith("registros="));
    if (registroCookie) {
        return JSON.parse(registroCookie.split("=")[1]); // Convertir de JSON a objeto
    }
    return []; // Si no hay registros, devolver un array vacío
}

// Función para cargar los registros desde cookies al cargar la página
function cargarRegistrosDesdeCookies() {
    let registros = obtenerRegistrosDeCookies();
    let catalogo = document.getElementById("catalogoRegistros");

    if (registros.length > 0) {
        catalogo.classList.remove("hidden"); // Mostrar el catálogo si hay registros
        registros.forEach((registro) => {
            let nuevoRegistro = document.createElement("div");
            nuevoRegistro.classList.add("registro");

            nuevoRegistro.innerHTML = `
                <p><strong>Nombre:</strong> ${limitarTexto(registro.nombre)}</p>
                <p><strong>Email:</strong> ${limitarTexto(registro.email)}</p>
                <p><strong>Teléfono:</strong> ${registro.telefono}</p>
                <p><strong>Comentarios:</strong> ${limitarTexto(registro.comentarios)}</p>
                <p><strong>País:</strong> ${registro.pais}</p>
                <p><strong>Intereses:</strong> ${limitarTexto(registro.intereses)}</p>
                <button class="eliminarBtn">Eliminar</button>
            `;

            catalogo.appendChild(nuevoRegistro);
        });
    }
}

// Función para limpiar el formulario
function limpiarFormulario() {
    document.getElementById("nombre").value = "";
    document.getElementById("email").value = "";
    document.getElementById("telefono").value = "";
    document.getElementById("comentarios").value = "";
    document.getElementById("pais").value = "";
    document.querySelectorAll('input[name="intereses"]').forEach((checkbox) => (checkbox.checked = false));
}

// Evento para eliminar un registro individual
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("eliminarBtn")) {
        event.target.parentElement.remove(); // Elimina el registro
        actualizarCookiesDespuesDeEliminar(); // Actualizar cookies
        if (document.getElementById("catalogoRegistros").children.length === 0) {
            document.getElementById("catalogoRegistros").classList.add("hidden"); // Oculta el catálogo si no hay registros
        }
    }
});

// Función para actualizar las cookies después de eliminar un registro
function actualizarCookiesDespuesDeEliminar() {
    let registros = [];
    document.querySelectorAll(".registro").forEach((registro) => {
        registros.push({
            nombre: registro.querySelector("p:nth-child(1)").textContent.replace("Nombre: ", ""),
            email: registro.querySelector("p:nth-child(2)").textContent.replace("Email: ", ""),
            telefono: registro.querySelector("p:nth-child(3)").textContent.replace("Teléfono: ", ""),
            comentarios: registro.querySelector("p:nth-child(4)").textContent.replace("Comentarios: ", ""),
            pais: registro.querySelector("p:nth-child(5)").textContent.replace("País: ", ""),
            intereses: registro.querySelector("p:nth-child(6)").textContent.replace("Intereses: ", ""),
        });
    });
    document.cookie = "registros=" + JSON.stringify(registros) + "; path=/"; // Actualizar cookies
}

// Evento para borrar todos los registros
document.getElementById("borrarBtn").addEventListener("click", function () {
    let catalogo = document.getElementById("catalogoRegistros");
    catalogo.innerHTML = ""; // Elimina todos los registros
    catalogo.classList.add("hidden"); // Oculta el catálogo
    document.cookie = "registros=; expires=Thu, 01 Jan 2026 00:00:00 UTC; path=/;"; // Eliminar cookies
});

// Cargar registros desde cookies al cargar la página
window.addEventListener("load", cargarRegistrosDesdeCookies);