document.addEventListener('DOMContentLoaded', function () {
  // Review form submit
  const reviewForm = document.querySelector('.review-form');
  if (reviewForm) {
    reviewForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const formData = new FormData(reviewForm);
      const response = await fetch('http://localhost/MaT_DoubleM/my-php-backend/public/index.php/reviews', {
        method: 'POST',
        body: formData
      });
      const result = await response.json();
      if (result.message) {
        window.location.href = 'review-confirmation.html';
      } else {
        alert('Eroare la trimiterea review-ului!');
      }
    });
  }

  // Fetch and display reviews
  const container = document.getElementById('reviews-container');
  if (container) {
    fetch('my-php-backend/public/index.php/reviews')
      .then(res => res.json())
      .then(data => {
        if (!data.reviews || data.reviews.length === 0) {
          container.innerHTML = "<p>Nu există recenzii încă.</p>";
          return;
        }
        container.innerHTML = data.reviews.map(r => `
          <div class="review">
            <blockquote style="margin-bottom: 20px;">
              „${r.text}”<br><br>- <b>${r.name}</b>
            </blockquote>
          </div>
        `).join('');
      });
  }
});