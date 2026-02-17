
    function toggleLetra(id) {
      const letra = document.getElementById(id);
      if (letra.style.display === "none" || letra.style.display === "") {
        letra.style.display = "block";
      } else {
        letra.style.display = "none";
      }
    }

