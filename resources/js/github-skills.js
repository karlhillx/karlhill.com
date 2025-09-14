import Chart from 'chart.js/auto';

async function loadGitHubStats() {
    const chartCanvas = document.getElementById('github-skills-chart');
    if (!chartCanvas) return;

    const ctx = chartCanvas.getContext('2d');

    try {
        const response = await fetch('/api/github/languages', { headers: { 'Accept': 'application/json' } });
        const data = await response.json();

        if (!Array.isArray(data)) {
            // If API returned an error object, log and bail gracefully
            console.warn('GitHub languages API returned non-array:', data);
            return;
        }

        const languageStats = {};
        data.forEach(repo => {
            if (repo && repo.language) {
                languageStats[repo.language] = (languageStats[repo.language] || 0) + 1;
            }
        });

        const labels = Object.keys(languageStats);
        const values = Object.values(languageStats);
        if (labels.length === 0 || values.length === 0) {
            // Nothing to chart
            return;
        }

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#4F46E5', '#06B6D4', '#3B82F6',
                        '#6366F1', '#A855F7'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: 'white'
                        }
                    }
                }
            }
        });
    } catch (e) {
        console.error('Failed to load GitHub language stats:', e);
    }
}

document.addEventListener('DOMContentLoaded', loadGitHubStats);

