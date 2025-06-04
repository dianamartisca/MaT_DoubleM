document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.reserv-form'); // selectează doar formularul de programare

  if (!form) return;

  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    const response = await fetch('http://localhost/MaT_DoubleM/my-php-backend/public/index.php/requests', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.message === "Request created successfully.") {
      window.location.href = 'request-confirmation.html';
    } 
  });
});