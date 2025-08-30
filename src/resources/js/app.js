import './bootstrap';
import './chart-parameter.js';
import './team-point-chart.js';
import './team-stats-chart.js';

import * as bootstrap from 'bootstrap';

// ページロード後にツールチップを初期化
document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
