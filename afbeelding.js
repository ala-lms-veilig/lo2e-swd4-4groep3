
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const closeBtn = document.querySelector('.close');

    document.querySelectorAll('.foto-box img').forEach(img => {
        img.addEventListener('click', function() {
            lightbox.style.display = 'flex'; 
            lightboxImg.src = this.src; 
        });
    });

    closeBtn.addEventListener('click', function() {
        lightbox.style.display = 'none'; // Verberg de lightbox
    });

    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            lightbox.style.display = 'none'; // Verberg de lightbox
        }
    });

