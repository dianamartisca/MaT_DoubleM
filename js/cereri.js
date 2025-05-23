
window.addEventListener("DOMContentLoaded", () => {
  fetchRequests();
});
//astept sa fie gata dom

function fetchRequests() {
  fetch('/MaT_DoubleM/my-php-backend/public/index.php/requests')
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
  <td>
    <textarea placeholder="Scrie raspunsul..."></textarea>
    <button class="send" onclick="submitResponse(${req.id}, this)">Trimite</button>
  </td>
  <td>
    <button class="approve-btn" onclick="approveRequest(${req.id}, this)">Aprobă</button>
    <button class="reject-btn" onclick="rejectRequest(${req.id}, this)">Respinge</button>
  </td>
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
  });
}


//pt aprove or not

function approveRequest(id, button) {
  fetch('/MaT_DoubleM/my-php-backend/public/index.php/requests/approve', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `id=${id}`
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.message) {
        button.parentElement.innerHTML = "<b style='color:green'>Aprobat</b>";
      } else {
        alert(resp.error || "Eroare la aprobare");
      }
    });
}

function rejectRequest(id, button) {
  fetch('/MaT_DoubleM/my-php-backend/public/index.php/requests/reject', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `id=${id}`
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.message) {
        button.parentElement.innerHTML = "<b style='color:red'>Respins</b>";
      } else {
        alert(resp.error || "Eroare la respingere");
      }
    });
}