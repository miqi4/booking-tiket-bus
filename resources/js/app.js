const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

function revealHomePage() {
    const homePage = document.querySelector('[data-page-home]');

    if (!homePage) {
        return;
    }

    const revealItems = Array.from(homePage.querySelectorAll('[data-reveal]'));

    if (prefersReducedMotion.matches) {
        document.body.classList.add('page-ready');
        revealItems.forEach((item) => item.classList.add('is-visible'));
        return;
    }

    requestAnimationFrame(() => {
        document.body.classList.add('page-ready');
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.18,
            rootMargin: '0px 0px -8% 0px',
        },
    );

    revealItems.forEach((item) => observer.observe(item));
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', revealHomePage, { once: true });
} else {
    revealHomePage();
}
