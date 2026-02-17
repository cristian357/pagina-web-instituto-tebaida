let index = 0;
        const carousel = document.querySelector(".carousel");
        const totalImages = document.querySelectorAll(".carousel .image-container").length;

        document.getElementById("next").addEventListener("click", () => {
            index = (index + 1) % totalImages;
            updateCarousel();
        });

        document.getElementById("prev").addEventListener("click", () => {
            index = (index - 1 + totalImages) % totalImages;
            updateCarousel();
        });

        function updateCarousel() {
            const offset = -index * 100;
            carousel.style.transform = `translateX(${offset}%)`;
        }

        setInterval(() => {
            index = (index + 1) % totalImages;
            updateCarousel();
        }, 3000);