/**
 * Sortable Table JavaScript
 * テーブルのソート機能を提供するJavaScript
 */

class SortableTable {
    constructor(tableSelector = '.sortable-table') {
        this.tables = document.querySelectorAll(tableSelector);
        this.init();
    }

    init() {
        this.tables.forEach(table => {
            const headers = table.querySelectorAll('.sortable-header');
            const tableBody = table.querySelector('tbody');

            headers.forEach(header => {
                header.addEventListener('click', () => {
                    this.handleSort(header, headers, tableBody);
                });
            });
        });
    }

    handleSort(clickedHeader, allHeaders, tableBody) {
        const sortBy = clickedHeader.dataset.sort;
        const currentDirection = clickedHeader.classList.contains('sort-asc') ? 'asc' : 'desc';
        const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

        // Reset all headers
        allHeaders.forEach(header => {
            header.classList.remove('sort-asc', 'sort-desc');
        });

        // Set current header
        clickedHeader.classList.add('sort-' + newDirection);

        // Sort table
        this.sortTable(tableBody, sortBy, newDirection);
    }

    sortTable(tableBody, sortBy, direction) {
        const rows = Array.from(tableBody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            let aValue, bValue;

            // スネークケースをキャメルケースに変換してdata属性にアクセス
            const dataAttribute = this.snakeToCamel(sortBy);

            switch(sortBy) {
                case 'player_rank':
                case 'match_count':
                    aValue = parseInt(a.dataset[dataAttribute]);
                    bValue = parseInt(b.dataset[dataAttribute]);
                    break;
                case 'sum_point':
                case 'top_ratio':
                case 'avoid_bottom_ratio':
                    aValue = parseFloat(a.dataset[dataAttribute]);
                    bValue = parseFloat(b.dataset[dataAttribute]);
                    break;
                case 'player_name':
                case 'team_name':
                    aValue = a.dataset[dataAttribute];
                    bValue = b.dataset[dataAttribute];
                    break;
                default:
                    return 0;
            }            if (typeof aValue === 'string') {
                aValue = aValue.toLowerCase();
                bValue = bValue.toLowerCase();
            }

            if (direction === 'asc') {
                return aValue < bValue ? -1 : aValue > bValue ? 1 : 0;
            } else {
                return aValue > bValue ? -1 : aValue < bValue ? 1 : 0;
            }
        });

        // Clear table body and add sorted rows
        tableBody.innerHTML = '';
        rows.forEach(row => tableBody.appendChild(row));
    }

    // スネークケースからキャメルケースに変換
    snakeToCamel(str) {
        return str.replace(/_([a-z])/g, (match, letter) => letter.toUpperCase());
    }

    // ケバブケースからキャメルケースに変換
    kebabToCamel(str) {
        return str.replace(/-([a-z])/g, (match, letter) => letter.toUpperCase());
    }

    // キャメルケースからケバブケースに変換
    camelToKebab(str) {
        return str.replace(/([A-Z])/g, '-$1').toLowerCase();
    }
}

// DOMContentLoadedで初期化
document.addEventListener('DOMContentLoaded', function() {
    new SortableTable();
});
