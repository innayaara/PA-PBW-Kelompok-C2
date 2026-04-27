document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Initialize Navbar Scroll Effect
    const initNavbarScroll = () => {
        const navbar = document.getElementById('navbar');
        if (!navbar) return;

        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        });
    };

    // 2. Initialize Scroll Reveal Animation
    const initScrollReveal = () => {
        const reveals = document.querySelectorAll('.reveal');
        if (reveals.length === 0) return;

        const observerOptions = {
            threshold: 0.12,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Once visible, we can stop observing this element
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        reveals.forEach(el => observer.observe(el));
    };

    // 3. Smooth Scroll for Anchor Links
    const initSmoothScroll = () => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    
                    const offset = 80; // Offset for fixed navbar
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    };

    // 4. Auto Close Mobile Navbar on Click
    const initMobileNavbarAutoClose = () => {
        const navbarCollapse = document.getElementById('navMenu');
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link:not(.btn-nav)');
        
        if (!navbarCollapse) return;

        // Using Bootstrap's Collapse instance if available, otherwise fallback to class removal
        const bsCollapse = window.bootstrap ? new bootstrap.Collapse(navbarCollapse, { toggle: false }) : null;

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) { // Only on mobile/tablet
                    if (bsCollapse) {
                        bsCollapse.hide();
                    } else {
                        navbarCollapse.classList.remove('show');
                    }
                }
            });
        });
    };

    // Execute Initializations
    initNavbarScroll();
    initScrollReveal();
    initSmoothScroll();
    initMobileNavbarAutoClose();

});