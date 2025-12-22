import Chart from 'chart.js/auto';

let githubChart = null;
let chartData = null;
let currentChartType = 'doughnut';

// Color palette for languages
const languageColors = {
    'JavaScript': '#F7DF1E',
    'TypeScript': '#3178C6',
    'Python': '#3776AB',
    'PHP': '#777BB4',
    'Java': '#ED8B00',
    'Go': '#00ADD8',
    'Rust': '#000000',
    'Ruby': '#CC342D',
    'C++': '#00599C',
    'C': '#A8B9CC',
    'C#': '#239120',
    'Swift': '#FA7343',
    'Kotlin': '#7F52FF',
    'Dart': '#0175C2',
    'HTML': '#E34F26',
    'CSS': '#1572B6',
    'Shell': '#89E051',
    'Vue': '#4FC08D',
    'React': '#61DAFB',
    'Angular': '#DD0031',
    'Svelte': '#FF3E00',
    'default': '#4F46E5'
};

function getLanguageColor(language) {
    return languageColors[language] || languageColors['default'];
}

function generateColors(count) {
    const colors = [
        '#4F46E5', '#06B6D4', '#3B82F6', '#6366F1', '#A855F7',
        '#EC4899', '#F59E0B', '#10B981', '#EF4444', '#8B5CF6'
    ];
    return colors.slice(0, count);
}

async function loadGitHubStats() {
    const chartCanvas = document.getElementById('github-skills-chart');
    const loadingEl = document.querySelector('.github-loading');
    const statsCards = document.querySelectorAll('.github-stat-card');
    
    if (!chartCanvas) return;

    const ctx = chartCanvas.getContext('2d');

    try {
        // Show loading state
        if (loadingEl) loadingEl.style.display = 'flex';
        
        // Add cache-busting and prevent caching
        const cacheBuster = `?t=${Date.now()}`;
        const response = await fetch(`/api/github/languages${cacheBuster}`, { 
            headers: { 
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            },
            cache: 'no-store'
        });
        const data = await response.json();
        
        // Debug logging
        console.log('GitHub API Response:', {
            totalRepos: Array.isArray(data) ? data.length : 0,
            reposWithLanguages: Array.isArray(data) ? data.filter(r => r && r.language).length : 0,
            languages: Array.isArray(data) ? [...new Set(data.filter(r => r && r.language).map(r => r.language))] : []
        });

        if (!Array.isArray(data)) {
            console.warn('GitHub languages API returned non-array:', data);
            if (loadingEl) loadingEl.style.display = 'none';
            return;
        }

        const languageStats = {};
        let reposWithLanguages = 0;
        let reposWithoutLanguages = 0;
        
        data.forEach(repo => {
            if (repo) {
                if (repo.language) {
                    languageStats[repo.language] = (languageStats[repo.language] || 0) + 1;
                    reposWithLanguages++;
                } else {
                    reposWithoutLanguages++;
                }
            }
        });
        
        console.log('Language Statistics:', {
            totalRepos: data.length,
            reposWithLanguages,
            reposWithoutLanguages,
            languages: Object.keys(languageStats),
            languageCounts: languageStats
        });

        chartData = {
            labels: Object.keys(languageStats),
            values: Object.values(languageStats),
            stats: languageStats
        };

        if (chartData.labels.length === 0 || chartData.values.length === 0) {
            if (loadingEl) loadingEl.style.display = 'none';
            return;
        }

        // Update stats cards with animation
        updateStatsCards(data, languageStats);

        // Generate colors based on languages
        const colors = chartData.labels.map(lang => getLanguageColor(lang));
        const generatedColors = generateColors(chartData.labels.length);

        // Create or update chart
        if (githubChart) {
            githubChart.destroy();
        }

        githubChart = new Chart(ctx, {
            type: currentChartType,
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.values,
                    backgroundColor: colors.length >= chartData.labels.length ? colors : generatedColors,
                    borderWidth: currentChartType === 'doughnut' ? 2 : 0,
                    borderColor: '#1e293b',
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateRotate: currentChartType === 'doughnut' || currentChartType === 'pie',
                    animateScale: true,
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false // We'll create custom legend
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                // For pie/doughnut charts, get value from dataset data array using dataIndex
                                const value = context.dataset.data[context.dataIndex];
                                const total = chartData.values.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : '0.0';
                                return `${label}: ${value} repo${value !== 1 ? 's' : ''} (${percentage}%)`;
                            }
                        }
                    }
                },
                onHover: (event, activeElements) => {
                    chartCanvas.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
                },
                onClick: (event, activeElements) => {
                    if (activeElements.length > 0) {
                        const index = activeElements[0].index;
                        const language = chartData.labels[index];
                        // Could open GitHub search or do something interactive
                        console.log('Clicked on:', language);
                    }
                }
            }
        });

        // Create custom legend
        createCustomLegend(chartData, colors.length >= chartData.labels.length ? colors : generatedColors);

        // Hide loading and show chart with animation
        setTimeout(() => {
            if (loadingEl) {
                loadingEl.style.opacity = '0';
                setTimeout(() => {
                    loadingEl.style.display = 'none';
                }, 300);
            }
            
            // Animate stats cards
            statsCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });
        }, 500);

    } catch (e) {
        console.error('Failed to load GitHub language stats:', e);
        if (loadingEl) loadingEl.style.display = 'none';
    }
}

function updateStatsCards(repos, languageStats) {
    const reposEl = document.querySelector('.github-stat-repos');
    const languagesEl = document.querySelector('.github-stat-languages');
    const topEl = document.querySelector('.github-stat-top');

    if (reposEl) {
        animateValue(reposEl, 0, repos.length, 1000);
    }
    
    if (languagesEl) {
        animateValue(languagesEl, 0, Object.keys(languageStats).length, 1000);
    }
    
    if (topEl) {
        const topLanguage = Object.keys(languageStats).reduce((a, b) => 
            languageStats[a] > languageStats[b] ? a : b
        );
        topEl.textContent = topLanguage || '-';
    }
}

function animateValue(element, start, end, duration) {
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            element.textContent = Math.round(end);
            clearInterval(timer);
        } else {
            element.textContent = Math.round(current);
        }
    }, 16);
}

function createCustomLegend(data, colors) {
    const legendEl = document.querySelector('.github-legend');
    if (!legendEl) return;

    legendEl.innerHTML = '';
    
    const total = data.values.reduce((a, b) => a + b, 0);
    
    data.labels.forEach((label, index) => {
        const value = data.values[index];
        const percentage = ((value / total) * 100).toFixed(1);
        const color = colors[index];
        
        const legendItem = document.createElement('div');
        legendItem.className = 'github-legend-item flex items-center gap-2 px-3 py-2 bg-white/5 hover:bg-white/10 backdrop-blur-lg rounded-lg border border-white/10 hover:border-white/20 transition-all duration-300 cursor-pointer group';
        legendItem.style.opacity = '0';
        legendItem.style.transform = 'translateY(10px)';
        
        legendItem.innerHTML = `
            <div class="w-3 h-3 rounded-full" style="background-color: ${color}; box-shadow: 0 0 8px ${color}40;"></div>
            <span class="text-white text-sm font-medium">${label}</span>
            <span class="text-white/60 text-xs">${value} (${percentage}%)</span>
        `;
        
        legendItem.addEventListener('click', () => {
            if (githubChart) {
                const meta = githubChart.getDatasetMeta(0);
                const clickedIndex = index;
                const currentHidden = meta.data[clickedIndex].hidden;
                meta.data.forEach((item, i) => {
                    if (i === clickedIndex) {
                        item.hidden = !currentHidden;
                    }
                });
                githubChart.update();
            }
        });
        
        legendItem.addEventListener('mouseenter', () => {
            if (githubChart) {
                const meta = githubChart.getDatasetMeta(0);
                if (meta.data[index]) {
                    meta.data[index].transition('active').draw();
                }
            }
        });
        
        legendEl.appendChild(legendItem);
        
        setTimeout(() => {
            legendItem.style.transition = 'opacity 0.4s ease-out, transform 0.4s ease-out';
            legendItem.style.opacity = '1';
            legendItem.style.transform = 'translateY(0)';
        }, index * 50);
    });
}

function changeChartType(type) {
    if (!chartData || !githubChart || type === currentChartType) return;
    
    currentChartType = type;
    
    // Update button states
    document.querySelectorAll('.github-chart-btn').forEach(btn => {
        if (btn.dataset.type === type) {
            btn.classList.add('active', 'bg-white/10', 'border-white/20', 'text-white');
            btn.classList.remove('bg-white/5', 'border-white/10', 'text-white/70');
        } else {
            btn.classList.remove('active', 'bg-white/10', 'border-white/20', 'text-white');
            btn.classList.add('bg-white/5', 'border-white/10', 'text-white/70');
        }
    });
    
    // Update chart type without reloading data
    const colors = chartData.labels.map(lang => getLanguageColor(lang));
    const generatedColors = generateColors(chartData.labels.length);
    const finalColors = colors.length >= chartData.labels.length ? colors : generatedColors;
    
    githubChart.config.type = type;
    githubChart.config.data.datasets[0].backgroundColor = finalColors;
    githubChart.config.data.datasets[0].borderWidth = type === 'doughnut' ? 2 : 0;
    githubChart.config.options.animation.animateRotate = type === 'doughnut' || type === 'pie';
    
    githubChart.update('active');
}

// Initialize chart type buttons
function initChartTypeButtons() {
    document.querySelectorAll('.github-chart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            changeChartType(btn.dataset.type);
        });
    });
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    loadGitHubStats();
    initChartTypeButtons();
});

