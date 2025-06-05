
window.addEventListener("DOMContentLoaded", () => {
  fetchRequests();
});
//astept sa fie gata dom

function fetchRequests() {
  const jwt = localStorage.getItem('jwt');
  fetch('/MaT_DoubleM/my-php-backend/public/index.php/requests', {
    headers: {
      'Authorization': 'Bearer ' + jwt
    }
  })
    .then((res) => {
      if (!res.ok) throw new Error("Cereri negasite");
      return res.json();
    })
    .then((data) => {
      console.log("Cereri primite:", data.message);
      displayRequests(data.message);
    })
    .catch((err) => console.error("Eroare la preluare cereri:", err));
}




//fetch la requests


function displayRequests(requests) {
  const masiniContainer = document.getElementById("masini");
  const bicicleteContainer = document.getElementById("biciclete");
  const trotineteContainer = document.getElementById("trotinete");

  requests.forEach((req) => {
    if (req.done === 1 || req.status.toLowerCase() !== "aprobata") return;
    const row = document.createElement("tr");
    row.innerHTML = `
  <td>${req.name}</td>
  <td>${req.date_requested}</td>
  <td>${req.description}</td>
  <td>
    ${req.images
        ? `<a href="/MaT_DoubleM/my-php-backend/uploads/${encodeURIComponent(req.images.trim().split(/[/\\]/).pop())}" target="_blank">Vezi fișier</a>`
        : "-"}
  </td>
  <td><button class="done-btn">Done</button></td>

`;

    switch (req.problem_type.toLowerCase()) {
      case "masina":
        masiniContainer.appendChild(row);
        break;
      case "bicicleta":
        bicicleteContainer.appendChild(row);
        break;
      case "trotineta":
        trotineteContainer.appendChild(row);
        break;
    }


    const btn = row.querySelector('.done-btn');
    btn.addEventListener('click', () => {
      fetch('http://localhost/MaT_DoubleM/my-php-backend/public/index.php/requests/done', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${req.id}`
      })
        .then(res => res.json())
        .then(result => {
          if (result.success) {
            btn.replaceWith('DONE');
            row.classList.add('completed');
          } else {
            alert("Eroare la salvare în DB");
          }
        });

    });

  });
}



