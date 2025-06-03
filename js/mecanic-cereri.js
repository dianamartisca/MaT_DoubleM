document.addEventListener('DOMContentLoaded', () => {
  const token = localStorage.getItem('token');
  if (!token) {
    alert('Nu ești autentificat.');
    window.location.href = '../auth/login.html';
    return;
  }

  fetch('http://localhost/MaT_DoubleM/my-php-backend/public/index.php/requests', {
    headers: { 'Authorization': `Bearer ${token}` }
  })
    .then(res => res.json())
    .then(data => {
      const aprobate = data.filter(r => r.status === 'aprobata');
      aprobate.forEach(renderRequest);
    });
});

function renderRequest(request) {
  const row = document.createElement('tr');
  row.innerHTML = `
    <td>${request.name}</td>
    <td>${request.date_requested}</td>
    <td>${request.description}</td>
    <td>${request.images ? `<a href="${request.images}" target="_blank">Vezi fișier</a>` : '-'}</td>
    <td><button class="done-btn">Done</button></td>
  `;

  row.querySelector('.done-btn').addEventListener('click', () => {
    row.classList.add('completed');
    row.querySelector('.done-btn').textContent = '✓';
    row.querySelector('.done-btn').disabled = true;
    // Aici poți trimite update la backend dacă vrei
  });

  const tip = request.problem_type.toLowerCase();
  const section = document.getElementById(tip); // masini, biciclete, trotinete
  if (section) section.appendChild(row);
}