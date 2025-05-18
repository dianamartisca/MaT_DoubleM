document.querySelector('.submit-btn').addEventListener('click', async function () {
  const produs = document.querySelector('input[name="produs"]').value;
  const furnizor = document.querySelector('input[name="furnizor"]').value;
  const cantitate = parseInt(document.querySelector('input[name="cantitate"]').value);
  const dataComanda = document.querySelector('input[name="data_comanda"]').value;

  const response = await fetch('http://localhost/MaT_DoubleM/my-php-backend/public/index.php/orders', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      produs: produs,
      furnizor: furnizor,
      cantitate: cantitate,
      data_comanda: dataComanda
    })
  });

  const result = await response.json();
  console.log(result);

  if (result.success) {
    alert("Comanda a fost înregistrată cu succes!");
    location.reload(); // sau reincarc dinamic
  } else {
    alert("Eroare la trimiterea comenzii: " + (result.error || "necunoscuta"));
  }
});


async function incarcaComenzi() {
  const response = await fetch('http://localhost/MaT_DoubleM/my-php-backend/public/index.php/orders');
  const comenzi = await response.json();

  const tbody = document.querySelector('table tbody');
  tbody.innerHTML = ''; // curat tabelul initial

  comenzi.forEach(comanda => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${comanda.produs}</td>
      <td>${comanda.furnizor}</td>
      <td>${comanda.cantitate}</td>
      <td>${comanda.data_comanda}</td>
    `;
    tbody.appendChild(tr);
  });
}

// Apeleaz functia la incarcarea paginii
document.addEventListener('DOMContentLoaded', incarcaComenzi);