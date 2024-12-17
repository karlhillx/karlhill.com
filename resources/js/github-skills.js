import Chart from 'chart.js/auto';

async function loadGitHubStats() {
    const chartCanvas = document.getElementById('github-skills-chart');
    if (!chartCanvas) return;

    const ctx = chartCanvas.getContext('2d');

    const response = await fetch('/api/github/languages');
    const repos = await response.json();

    const languageStats = {};
    repos.forEach(repo => {
        if (repo.language) {
            languageStats[repo.language] = (languageStats[repo.language] || 0) + 1;
        }
    });

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(languageStats),
            datasets: [{
                data: Object.values(languageStats),
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
}

document.addEventListener('DOMContentLoaded', loadGitHubStats);

