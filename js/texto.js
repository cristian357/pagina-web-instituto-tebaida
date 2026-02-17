function toggleContenido() {
      const parrafo = document.getElementById("parrafoOculto");
      const boton = document.querySelector(".boton-mostrar");

      if (parrafo.style.display === "none" || parrafo.style.display === "") {
        parrafo.style.display = "block";
        boton.textContent = "🔼 Ocultar";
      } else {
        parrafo.style.display = "none";
        boton.textContent = "🔽 Mostrar más";
      }
    }