<?php
require_once 'config/database.php';
include 'header.php';
?>

    <div class="slider">
        <div class="slides" id="slides">
            <img src="images/slide1.jpg" alt="Слайд 1" class="slide">
            <img src="images/slide2.jpg" alt="Слайд 2" class="slide">
            <img src="images/slide3.jpg" alt="Слайд 3" class="slide">
            <img src="images/slide4.jpg" alt="Слайд 4" class="slide">
        </div>
        <button class="slider-btn prev" onclick="prevSlide()">←</button>
        <button class="slider-btn next" onclick="nextSlide()">→</button>
    </div>

    <div class="content">
        <h1>Добро пожаловать</h1>
        <p>Оставьте заявку на наши услуги</p>
    </div>

    <script>
        let index = 0;
        const slides = document.getElementById('slides');
        const total = slides.children.length;
        function update() { slides.style.transform = `translateX(-${index * 100}%)`; }
        function nextSlide() { index = (index + 1) % total; update(); resetTimer(); }
        function prevSlide() { index = (index - 1 + total) % total; update(); resetTimer(); }
        let timer = setInterval(nextSlide, 3000);
        function resetTimer() { clearInterval(timer); timer = setInterval(nextSlide, 3000); }
    </script>

<?php include 'footer.php'; ?>