import Chart from "chart.js/auto";
import zoomPlugin from 'chartjs-plugin-zoom'; // zoomプラグインのインポート

// プラグインを登録
Chart.register(zoomPlugin);

/**
 * チームポイント推移表を生成する
 *
 * @param {*} id
 * @param {*} data
 * @param {*} startDateForGraph グラフ用の開始日付
 */
window.makeTeamPointChart = function (id, data, startDateForGraph) {

    /**
     * チームポイントを取得する
     * @param {*} teamName
     * @param {*} datasets
     * @returns
     */
    const getTeamPoint = function (teamName, datasets) {
        // チーム名でフォルターしているため、必ず1つに絞り込まれる
        // また、配列での結果取得は不要なので[0]を指定している
        const dataset = datasets.filter(element => {
            return element.label === teamName;
        })[0];

        // ポイントには累積ポイントが格納されているため
        // 最後の値を取得すればOK
        return parseFloat(dataset.data.at(-1));
    };

    /**
     * 凡例をチームポイントで降順でソートする
     * @param {LegendItem} a 比較のための最初の要素。未定義になることはない。
     * @param {LegendItem} b 比較のための2番目の要素。未定義になることはない。
     * @param {ChartData} data
     */
    const sortLegend = function (a, b, data) {
        // 2番目の値が大きい場合、降順でソートされる
        return getTeamPoint(b.text, data.datasets) - getTeamPoint(a.text, data.datasets);
    };

    const ctx = document.getElementById(id);
    return new Chart(ctx, {
        type: "line",
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { // 凡例
                    display: true,
                    position: 'right',
                    labels: {
                        color: '#ffffff',
                        padding: 20,
                        sort: sortLegend,
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
                y: {
                    border: {
                        display: false,
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
            },
        },
    });
};

document.addEventListener('DOMContentLoaded', () => {

    if (document.body.dataset.page != 'teamPointChart') {
        return;
    }

    // グラフの初期化
    const myChart = globalThis.makeTeamPointChart(window.idForGraph, window.dataForGraph, window.startDateForGraph);

    // リセットボタン
    const resetButton = document.getElementById('resetZoomButton');
    if (resetButton == null) {
        return;
    }
    resetButton.addEventListener('click', () => {
        myChart.resetZoom();
    });
});
