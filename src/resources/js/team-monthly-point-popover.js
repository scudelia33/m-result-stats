import * as bootstrap from 'bootstrap';
import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    const popoverTriggerList = document.querySelectorAll('.team-monthly-point-cell[data-bs-toggle="popover"]');

    popoverTriggerList.forEach(function (popoverTriggerEl) {
        let popoverInstance = null;
        let cachedData = null;

        popoverTriggerEl.addEventListener('click', async function (e) {
            e.preventDefault();

            if (popoverInstance) {
                popoverInstance.dispose();
                popoverInstance = null;
                cachedData = null;
                return;
            }

            const teamId = parseInt(this.dataset.teamId, 10);
            const year = parseInt(this.dataset.year, 10);
            const month = parseInt(this.dataset.month, 10);
            const seasonId = parseInt(this.dataset.seasonId, 10);
            const matchCategoryId = parseInt(this.dataset.matchCategoryId, 10);

            console.log('Popover clicked with params:', { teamId, year, month, seasonId, matchCategoryId });

            popoverInstance = new bootstrap.Popover(popoverTriggerEl, {
                title: '読込中...',
                content: '<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                html: true,
                sanitize: false,
                trigger: 'manual',
                placement: 'top',
                container: 'body',
                customClass: 'team-monthly-point-popover'
            });

            popoverInstance.show();

            try {
                if (!cachedData) {
                    const response = await axios.get('/api/team-monthly-player-points', {
                        params: {
                            team_id: teamId,
                            year: year,
                            month: month,
                            season_id: seasonId,
                            match_category_id: matchCategoryId
                        }
                    });

                    cachedData = response.data;
                }

                const data = cachedData;

                let contentHtml = '';

                if (data.data && data.data.length > 0) {
                    contentHtml = '<div class="player-points-list" style="max-height: 300px; overflow-y: auto;">';
                    contentHtml += '<table class="table table-sm table-hover mb-0">';
                    contentHtml += '<thead><tr><th>選手</th><th class="text-end">ポイント</th></tr></thead>';
                    contentHtml += '<tbody>';

                    data.data.forEach(player => {
                        const netPointClass = player.net_point < 0 ? 'text-danger' : '';
                        contentHtml += '<tr>';
                        contentHtml += `<td>${player.player_name}</td>`;
                        contentHtml += `<td class="text-end fw-bold ${netPointClass}">${player.net_point.toFixed(1)}</td>`;
                        contentHtml += '</tr>';
                    });

                    contentHtml += '</tbody>';
                    contentHtml += '</table>';
                    contentHtml += '</div>';
                } else {
                    contentHtml = '<div class="text-center text-muted">データがありません</div>';
                }

                popoverInstance.dispose();

                popoverInstance = new bootstrap.Popover(popoverTriggerEl, {
                    title: `${data.team_name} - ${data.month}`,
                    content: contentHtml,
                    html: true,
                    sanitize: false,
                    trigger: 'manual',
                    placement: 'top',
                    container: 'body',
                    customClass: 'team-monthly-point-popover'
                });

                popoverInstance.show();

                const hidePopover = function () {
                    if (popoverInstance) {
                        popoverInstance.dispose();
                        popoverInstance = null;
                    }
                };

                document.addEventListener('click', function clickOutside(e) {
                    if (!popoverTriggerEl.contains(e.target) && !document.querySelector('.popover')?.contains(e.target)) {
                        hidePopover();
                        document.removeEventListener('click', clickOutside);
                    }
                });

            } catch (error) {
                console.error('Error fetching player points:', error);
                console.error('Error response:', error.response);
                console.error('Error data:', error.response?.data);

                popoverInstance.dispose();

                let errorMessage = 'データの取得に失敗しました';
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    errorMessage += '<br><small>' + Object.values(errors).flat().join('<br>') + '</small>';
                } else if (error.response?.data?.message) {
                    errorMessage += '<br><small>' + error.response.data.message + '</small>';
                }

                popoverInstance = new bootstrap.Popover(popoverTriggerEl, {
                    title: 'エラー',
                    content: `<div class="text-danger">${errorMessage}</div>`,
                    html: true,
                    trigger: 'manual',
                    placement: 'top',
                    container: 'body',
                    customClass: 'team-monthly-point-popover'
                });

                popoverInstance.show();

                setTimeout(() => {
                    if (popoverInstance) {
                        popoverInstance.dispose();
                        popoverInstance = null;
                    }
                }, 5000);
            }
        });
    });
});
