
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
  ${req.status === "aprobata" || req.status === "respinsa"
    ? "-"
    : `<textarea placeholder="Scrie raspunsul..."></textarea>
       <button class="send" onclick="submitResponse(${req.id}, this)">Trimite</button>`}
</td>

  <td>
  ${req.status === "aprobata"
    ? `<b style='color:green'>Aprobat</b><br>
       <button class="edit-btn" onclick="editRequest(${req.id}, this)">Editează</button>
       <button class="delete-btn" onclick="deleteRequest(${req.id}, this)">Șterge</button>`
    : req.status === "respinsa"
      ? `<b style='color:red'>Respins</b><br>
         <button class="edit-btn" onclick="editRequest(${req.id}, this)">Editează</button>
         <button class="delete-btn" onclick="deleteRequest(${req.id}, this)">Șterge</button>`
      : `
         <button class="approve-btn" onclick="approveRequest(${req.id}, this)">Aprobă</button>
         <button class="reject-btn" onclick="rejectRequest(${req.id}, this)">Respinge</button>`
  }
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
  const jwt = localStorage.getItem('jwt');
  fetch('/MaT_DoubleM/my-php-backend/public/index.php/requests/approve', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Authorization': 'Bearer ' + jwt
    },
    body: `id=${id}`
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.message) {
        // button.parentElement.innerHTML = "<b style='color:green'>Aprobat</b>";

        button.parentElement.innerHTML = `
  <b style='color:green'>Aprobat</b><br>
  <button class="edit-btn" onclick="editRequest(${id}, this)">Editează</button>
  <button class="delete-btn" onclick="deleteRequest(${id}, this)">Șterge</button>
`;
      } else {
        alert(resp.error || "Eroare la aprobare");
      }
    });
}

function rejectRequest(id, button) {
  const jwt = localStorage.getItem('jwt');
  fetch('/MaT_DoubleM/my-php-backend/public/index.php/requests/reject', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Authorization': 'Bearer ' + jwt
    },
    body: `id=${id}`
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.message) {
       // button.parentElement.innerHTML = "<b style='color:red'>Respins</b>";

       button.parentElement.innerHTML = `
  <b style='color:red'>Respins</b><br>
  <button class="edit-btn" onclick="editRequest(${id}, this)">Editează</button>
  <button class="delete-btn" onclick="deleteRequest(${id}, this)">Șterge</button>
`;
      } else {
        alert(resp.error || "Eroare la respingere");
      }
    });
}

//pt stergere cereri si revenire la approve

function deleteRequest(id, button) {
  const jwt = localStorage.getItem('jwt');
  if (!confirm("Ești sigur că vrei să ștergi această cerere?")) return;

  fetch('/MaT_DoubleM/my-php-backend/public/index.php/requests/delete', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Authorization': 'Bearer ' + jwt
    },
    body: `id=${id}`
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.message) {
        button.closest("tr").remove(); // sterg vizual
      } else {
        alert(resp.error || "Eroare la ștergere");
      }
    });
}

function editRequest(id, button) {
  const jwt = localStorage.getItem('jwt');

  fetch('/MaT_DoubleM/my-php-backend/public/index.php/requests/reset-status', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Authorization': 'Bearer ' + jwt
    },
    body: `id=${id}`
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.message) {
        location.reload();
      } else {
        alert(resp.error || "Eroare la editare");
      }
    });
}

function submitResponse(id, button) {
  const jwt = localStorage.getItem('jwt');
  const textarea = button.previousElementSibling;
  const response = textarea.value;

  fetch('/MaT_DoubleM/my-php-backend/public/index.php/requests/respond', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Authorization': 'Bearer ' + jwt
    },
    body: `id=${encodeURIComponent(id)}&response=${encodeURIComponent(response)}`
  })
    .then(res => res.json())
    .then(resp => {
      if (resp.message) {
        alert("Răspunsul a fost trimis pe email!");
        location.reload();
      } else {
        alert(resp.error || "Eroare la trimiterea răspunsului");
      }
    });
}