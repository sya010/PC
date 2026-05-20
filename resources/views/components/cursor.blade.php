<div x-data="" x-init="
    const dot = $el.querySelector('.cursor-dot');
    const ring = $el.querySelector('.cursor-ring');
    
    // Initial position center or off-screen
    let mouseX = -100;
    let mouseY = -100;
    
    // Smooth follow variables
    let ringX = -100;
    let ringY = -100;

    window.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        
        // Dot moves instantly
        dot.style.transform = `translate(${mouseX}px, ${mouseY}px)`;
    });

    // Ring smooth follow loop
    const animateRing = () => {
        ringX += (mouseX - ringX) * 0.15; // smooth factor
        ringY += (mouseY - ringY) * 0.15;
        
        ring.style.transform = `translate(${ringX}px, ${ringY}px)`;
        requestAnimationFrame(animateRing);
    };
    animateRing();
" class="pointer-events-none fixed inset-0 z-[9999] hidden lg:block">
    <div class="cursor-dot fixed top-0 left-0 w-2 h-2 bg-brand-accent rounded-full -translate-x-1/2 -translate-y-1/2 mix-blend-difference pointer-events-none shadow-[0_0_10px_var(--color-brand-accent)]"></div>
    <div class="cursor-ring fixed top-0 left-0 w-8 h-8 border border-brand-accent/50 rounded-full -translate-x-1/2 -translate-y-1/2 transition-transform duration-75 ease-out pointer-events-none backdrop-blur-[1px]"></div>
    
    <style>
        body { cursor: none; }
        a:hover ~ div .cursor-ring, button:hover ~ div .cursor-ring {
            transform: scale(1.5);
            background: color-mix(in srgb, var(--color-brand-accent) 10%, transparent);
            border-color: color-mix(in srgb, var(--color-brand-accent) 80%, transparent);
        }
    </style>
</div>
