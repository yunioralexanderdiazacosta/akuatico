import fs from "fs";

// Nombres de los archivos
// Asegúrate de que 'cities.json' sea el nombre real de tu archivo de 44MB
const archivoOriginal = "cities.json";
const archivoDestino = "ciudades_us_pr.json";

try {
    console.log(
        "Iniciando lectura del archivo... Esto puede tardar unos segundos debido al tamaño.",
    );

    // Leemos el archivo original de forma sincrónica
    // Con 44MB, Node.js puede manejarlo perfectamente en memoria
    const contenido = fs.readFileSync(archivoOriginal, "utf8");

    // Convertimos el texto JSON a un array de objetos JavaScript
    const todasLasCiudades = JSON.parse(contenido);

    console.log(`Se encontraron ${todasLasCiudades.length} ciudades en total.`);
    console.log(
        "Filtrando ciudades de Estados Unidos (US) y Puerto Rico (PR)...",
    );

    // Filtramos el array buscando los códigos de país específicos
    const ciudadesFiltradas = todasLasCiudades.filter(
        (ciudad) =>
            ciudad.country_code === "US" || ciudad.country_code === "PR",
    );

    console.log(
        `Proceso completado. Se seleccionaron ${ciudadesFiltradas.length} ciudades.`,
    );

    // Guardamos el resultado en un nuevo archivo JSON formateado para que sea legible (con indentación de 2 espacios)
    fs.writeFileSync(
        archivoDestino,
        JSON.stringify(ciudadesFiltradas, null, 2),
        "utf8",
    );

    console.log(
        `¡Éxito! El nuevo archivo ha sido generado como: ${archivoDestino}`,
    );
} catch (error) {
    // Manejo de errores en caso de que el archivo no exista o el JSON esté mal formado
    console.error("Hubo un error al procesar los archivos:", error.message);
}
