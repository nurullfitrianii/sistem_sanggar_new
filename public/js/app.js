document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi AOS
    AOS.init({ duration: 1000, once: false, mirror: true });

    // 2. GSAP: Animasi Huruf Tanpa Rusak Layout
    const heroTitle = document.querySelector('header h2');
    if (heroTitle) {
        const originalText = heroTitle.textContent.trim();
        heroTitle.innerHTML = originalText.split('').map(char => {
            return char === ' ' ? ' ' : `<span class='inline-block'>${char}</span>`;
        }).join('');

        gsap.from(heroTitle.querySelectorAll('span'), {
            duration: 1,
            opacity: 0,
            y: 40,
            stagger: 0.04,
            ease: "back.out(1.7)",
            delay: 0.3
        });
    }

    // 3. Navbar Scroll
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('nav');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
});
