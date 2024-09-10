const starRating = document.querySelector('.star-rating');
const stars = starRating.querySelectorAll('i');

let rating = 0;

stars.forEach((star, index) => {
  star.addEventListener('click', () => {
    rating = index + 1;
    stars.forEach((star, i) => {
      if (i < rating) {
        star.classList.add('active');
      } else {
        star.classList.remove('active');
      }
    });
  });
});







