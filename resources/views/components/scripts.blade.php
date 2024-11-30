@vite('resources/js/app.js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const darkMode = localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', darkMode);
    });
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-EZZNL8KY8P"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'G-EZZNL8KY8P');
</script>
