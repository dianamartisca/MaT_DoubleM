
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
  ${req.images ? (() => {
        const fileName = req.images.split("uploads/").pop();
        return `<a href="/MaT_DoubleM/my-php-backend/public/downloads.php?file=
        ${encodeURIComponent(fileName)}" target="_blank">Vezi fișiere</a>`;
        
      })() : "-"}
</td>
      <td>
        <textarea placeholder="Scrie răspunsul pentru client..."></textarea>
        <button onclick="submitResponse(${req.id}, this)">Trimite</button>
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