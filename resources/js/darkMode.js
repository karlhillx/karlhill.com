export default () => ({
    darkMode: localStorage.getItem('darkMode') === 'true',
    init() {
        this.$watch('darkMode', val => {
            localStorage.setItem('darkMode', val)
            document.documentElement.classList.toggle('dark', val)
        })

        // Initialize on page load
        document.documentElement.classList.toggle('dark', this.darkMode)
    }
})
