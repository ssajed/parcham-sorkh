import './jalali-date-picker';


// Register the PWA worker. It caches static assets only, never private pages or data.
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js', { scope: '/' }).catch(() => {
            // The site remains fully usable when service workers are unavailable.
        });
    });
}
