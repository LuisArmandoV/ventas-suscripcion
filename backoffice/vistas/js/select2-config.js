$(document).ready(function() {
    $(".select2").select2(); // Activamos Select2

    // 🔹 1️⃣ Datos de departamentos y municipios
    const datosColombia = {
        "Antioquia": ["Medellín", "Bello", "Envigado", "Itagüí", "Sabaneta", "Caldas", "Copacabana", 
            "Barbosa", "Rionegro", "Marinilla", "Guarne", "San Vicente", "El Retiro", "La Ceja", "San Rafel"],
        "Cundinamarca": ["Bogotá", "Soacha", "Chía", "Zipaquirá"],
        "Valle del Cauca": ["Cali", "Palmira", "Tuluá", "Buenaventura"],
        "Atlántico": ["Barranquilla", "Soledad", "Malambo", "Puerto Colombia"],
        "Risaralda": ["Pereira"],
        "Caldas": ["Manizales"]
    };

    // 🔹 2️⃣ Referencias a los selects
    const selectDepartamento = $("#inputDepartamento");
    const selectMunicipio = $("#inputMunicipio");

    // 🔹 3️⃣ Llenar departamentos
    for (let departamento in datosColombia) {
        selectDepartamento.append(new Option(departamento, departamento));
    }

    // 🔹 4️⃣ Evento cuando seleccionan un departamento
    selectDepartamento.on("change", function() {
        const deptoSeleccionado = $(this).val();
        selectMunicipio.empty().append(new Option("Seleccione su municipio", ""));

        if (deptoSeleccionado) {
            datosColombia[deptoSeleccionado].forEach(municipio => {
                selectMunicipio.append(new Option(municipio, municipio));
            });
        }
    });
});
