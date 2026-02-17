document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("hora");
    const horaOutput = document.getElementById("hora-valor");
    const inputHidden = document.getElementById("hora_real");
  
    // Horas traducidas (puedes ajustar según tu horario real)
    const horas = {
      1: "07:00",
      2: "08:00",
      3: "09:00",
      4: "10:00",
      5: "11:00",
      6: "12:00"
    };
  
    function actualizarHora() {
      const valor = input.value;
      const horaTexto = horas[valor];
      horaOutput.textContent = horaTexto;
      inputHidden.value = horaTexto;
    }
  
    input.addEventListener("input", actualizarHora);
    actualizarHora(); // inicializa cuando se carga
  });
  
