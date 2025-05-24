document.addEventListener("DOMContentLoaded", () => {
  const tbody = document.querySelector("tbody");
  const jwt = localStorage.getItem('jwt');

  fetch("/MaT_DoubleM/my-php-backend/public/index.php/piese", {
    headers: {
      'Authorization': 'Bearer ' + jwt
    }
  })
    .then(res => res.json())
    .then(data => {
      tbody.innerHTML = "";
      data.forEach(item => {
        const row = document.createElement("tr");

        row.innerHTML = `
          <td>${item.denumire}</td>
          <td>${item.categorie}</td>
          <td>${item.cantitate}</td>
          <td><button class="edit-btn" data-id="${item.id}">Editează</button></td>
        `;

        tbody.appendChild(row);
      });

      activateEditButtons();
    });

  function activateEditButtons() {
    const editButtons = document.querySelectorAll(".edit-btn");

    editButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        const row = btn.closest("tr");
        const cells = row.querySelectorAll("td");
        const id = btn.getAttribute("data-id");

        if (btn.textContent === "Editează") {
          const currentQty = cells[2].textContent.trim();
          cells[2].innerHTML = `<input type='number' value='${currentQty}' min='0'>`;
          btn.textContent = "Salvează";
        } else {
          const input = cells[2].querySelector("input");
          const newQty = input.value;

          fetch("/MaT_DoubleM/my-php-backend/public/index.php/piese/update", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Authorization": "Bearer " + jwt
            },
            body: JSON.stringify({
              id: id,
              cantitate: newQty
            })
          })
            .then(res => res.json())
            .then(result => {
              if (result.message) {
                cells[2].textContent = newQty;
                btn.textContent = "Editează";
              } else {
                alert(result.error || "Eroare la actualizare.");
              }
            });
        }
      });
    });
  }

  const addForm = document.getElementById("addForm");
  if (addForm) {
    addForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const denumire = document.getElementById("denumire").value.trim();
      const categorie = document.getElementById("categorie").value.trim();
      const cantitate = parseInt(document.getElementById("cantitate").value);

      fetch("/MaT_DoubleM/my-php-backend/public/index.php/piese", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + jwt
        },
        body: JSON.stringify({ denumire, categorie, cantitate })
      })
        .then(res => res.json())
        .then(result => {
         // alert(result.message || result.error);
          location.reload();
        });
    });
  }
});