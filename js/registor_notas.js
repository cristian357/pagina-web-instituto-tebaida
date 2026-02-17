let filaIndex = 1;
let numeroNotas = 4;

function agregarFila() {
    const tabla = document.getElementById('tabla-notas');
    const fila = document.createElement('tr');

    let celdasNotas = '';
    for (let i = 0; i < numeroNotas; i++) {
        celdasNotas += `<td><input type="number" name="notas[${filaIndex}][]" step="0.1" min="0" max="5"></td>`;
    }

    fila.innerHTML = `
        <td>
            <select name="periodos[]" required>
                <option value="">Seleccione</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </td>
        ${celdasNotas}
        <td><button type="button" onclick="eliminarFila(this)">🔚</button></td>
    `;

    tabla.appendChild(fila);
    filaIndex++;
}

function eliminarFila(boton) {
    const fila = boton.closest('tr');
    fila.remove();
}

function agregarColumnaNota() {
    numeroNotas++;
    const encabezado = document.querySelector("#encabezado-notas tr");
    const nuevaTh = document.createElement('th');
    nuevaTh.className = 'nota-col';
    nuevaTh.textContent = `Nota ${numeroNotas}`;
    encabezado.insertBefore(nuevaTh, encabezado.lastElementChild);

    const filas = document.querySelectorAll("#tabla-notas tr");
    filas.forEach((fila, index) => {
        const nuevaTd = document.createElement('td');
        nuevaTd.innerHTML = `<input type="number" name="notas[${index}][]" step="0.1" min="0" max="5">`;
        fila.insertBefore(nuevaTd, fila.lastElementChild);
    });
}

function eliminarColumnaNota() {
    if (numeroNotas <= 1) return;
    numeroNotas--;

    const ths = document.querySelectorAll('#encabezado-notas .nota-col');
    ths[ths.length - 1].remove();

    const filas = document.querySelectorAll('#tabla-notas tr');
    filas.forEach(fila => {
        const tds = fila.querySelectorAll('td');
        tds[tds.length - 2].remove();
    });
}