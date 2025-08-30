import Chart from "chart.js/auto";
import zoomPlugin from 'chartjs-plugin-zoom'; // zoomプラグインのインポート

// プラグインを登録
Chart.register(zoomPlugin);

/**
 * チームスタッツ表を生成する
 *
 * @param {*} id
 * @param {*} data
 * @param {*} startDateForGraph グラフ用の開始日付
 * @param {*} teamCount チーム数
 */
window.makeTeamStatsChart = function (id, data, startDateForGraph, titleText, teamCount) {
    const ctx = document.getElementById(id);
    return new Chart(ctx, {
        type: "line",
        data: data,
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                title: { // タイトル
                    display: true,
                    text: titleText,
                    color: '#ffffff',
                    font: {
                        size: 12,
                    },
                },
                legend: { // 凡例
                    display: true,
                    position: 'right',
                    labels: {
                        color: '#ffffff',
                        padding: 20,
                    }
                },
                zoom: {
                    pan: {
                        enabled: true,
                        mode: 'xy',
                        threshold: 5,
                    },
                    zoom: {
                        wheel: {
                            enabled: true
                        },
                        pinch: {
                            enabled: true
                        },
                        mode: 'xy',
                    },
                },
            },
            scales: {
                x: {
                    ticks: {
                        callback: function(val, index) {
                            // グラフ用の開始日付は目盛ラベルに表示しない
                            return this.getLabelForValue(val) === startDateForGraph ? '' : this.getLabelForValue(val);
                        },
                        color: '#ffffff',
                        font: {
                            size: 14,
                        },
                    },
                },
                y: { // ポイント
                    type: 'linear',
                    display: true,
                    title: {
                        display: true,
                        text: 'ポイント',
                        color: '#ffffff',
                        font: {
                            size: 14,
                        },
                    },
                    position: 'left',
                    ticks: {
                        color: '#ffffff',
                        font: {
                            size: 14,
                        },
                    },
                    grid: {
                        color: context => context.tick.value === 0 ? '#ffffff' : '#888888'
                    },
                    ticks: {
                        color: '#ffffff',
                        font: {
                            size: 14,
                        },
                    },
                },
                y1: { // 順位
                    type: 'linear',
                    display: true,
                    title: {
                        display: true,
                        text: '順位',
                        color: '#ffffff',
                        font: {
                            size: 14,
                        },
                    },
                    position: 'right',
                    reverse: true, // 順位なので、値の小さい方を上位にする
                    ticks: {
                        color: '#ffffff',
                        font: {
                            size: 14,
                        },
                    },
                    min: 1,
                    max: teamCount, // チーム数が最下位の順位になる
                },
            },
        },
    });
};

document.addEventListener('DOMContentLoaded', () => {
    if (document.body.dataset.page != 'teamStatsChart') {
        return;
    }

    // グラフの初期化
    const myChart = globalThis.makeTeamStatsChart(window.idForGraph, window.dataForGraph, window.startDateForGraph, window.titleTextForGraph, window.teamCountForGraph);

    // リセットボタン
    const resetButton = document.getElementById('resetZoomButton');
    if (resetButton == null) {
        return;
    }
    resetButton.addEventListener('click', () => {
        myChart.resetZoom();
    });
});
