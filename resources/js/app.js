// Mobile menu: toggles .menu-open on <body>, closes on Escape or link click.
const toggle = document.querySelector('[data-menu-toggle]');
const menu = document.querySelector('[data-menu]');

if (toggle && menu) {
    const setOpen = (open) => {
        document.body.classList.toggle('menu-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        toggle.querySelector('[data-menu-open]').toggleAttribute('hidden', open);
        toggle.querySelector('[data-menu-close]').toggleAttribute('hidden', !open);
    };

    toggle.addEventListener('click', () => setOpen(!document.body.classList.contains('menu-open')));
    menu.addEventListener('click', (event) => {
        if (event.target.closest('a')) setOpen(false);
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && document.body.classList.contains('menu-open')) {
            setOpen(false);
            toggle.focus();
        }
    });
    window.matchMedia('(min-width: 1081px)').addEventListener('change', (event) => {
        if (event.matches) setOpen(false);
    });
}

// Solid header once the page scrolls past the hero top.
if (document.body.classList.contains('has-overlay-header')) {
    const update = () => document.body.classList.toggle('is-scrolled', window.scrollY > 40);
    update();
    window.addEventListener('scroll', update, { passive: true });
}

// Subtle reveal on scroll; CSS shows everything when reduced motion is preferred.
const items = document.querySelectorAll('.reveal');

if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px' });

    items.forEach((item) => observer.observe(item));
} else {
    items.forEach((item) => item.classList.add('is-visible'));
}
