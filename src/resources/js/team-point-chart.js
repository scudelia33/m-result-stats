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
    // レジェンド用のツールチップDOM要素を作成（レジェンド上ホバーで最終ポイントを表示するため）
    let legendTooltipDiv = null;
    const ensureLegendTooltip = () => {
        if (legendTooltipDiv) return legendTooltipDiv;
        legendTooltipDiv = document.createElement('div');
        legendTooltipDiv.style.position = 'fixed';
        legendTooltipDiv.style.pointerEvents = 'none';
        legendTooltipDiv.style.background = 'rgba(0,0,0,0.8)';
        legendTooltipDiv.style.color = '#fff';
        legendTooltipDiv.style.padding = '6px 8px';
        legendTooltipDiv.style.borderRadius = '4px';
        legendTooltipDiv.style.fontSize = '13px';
        legendTooltipDiv.style.zIndex = 9999;
        legendTooltipDiv.style.display = 'none';
        document.body.appendChild(legendTooltipDiv);
        return legendTooltipDiv;
    };
    const showLegendTooltip = (text, clientX, clientY) => {
        const div = ensureLegendTooltip();
        div.textContent = text;
        const offsetX = 12;
        const offsetY = 12;
        // 位置計算のため一旦表示してサイズを測る（ただし可視化は後で行う）
        div.style.visibility = 'hidden';
        div.style.display = 'block';
        div.style.transform = '';
        const tooltipWidth = div.offsetWidth || 0;

        // 優先: マウスの左下に表示するが、左端にはみ出す場合は右下に切り替える
        if (clientX - offsetX - tooltipWidth < 8) {
            // 右下に表示
            div.style.left = `${clientX + offsetX}px`;
            div.style.transform = '';
        } else {
            // 左下に表示（幅分左へ移動）
            div.style.left = `${clientX - offsetX}px`;
            div.style.transform = 'translateX(-100%)';
        }
        div.style.top = `${clientY + offsetY}px`;
        div.style.visibility = 'visible';
    };
    const hideLegendTooltip = () => {
        if (!legendTooltipDiv) return;
        legendTooltipDiv.style.display = 'none';
        legendTooltipDiv.style.transform = '';
    };
    return new Chart(ctx, {
        type: "line",
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { // 凡例
                    display: true,
                    position: 'right',
                    // 凡例上ホバーで最終ポイントを表示する
                    onHover: function(event, legendItem, legend) {
                        try {
                            const chart = legend.chart;
                            const ds = chart.data.datasets[legendItem.datasetIndex];
                            const last = ds && Array.isArray(ds.data) && ds.data.length ? ds.data[ds.data.length - 1] : null;
                            const formatted = last === null ? '-' : Number(last).toLocaleString();
                            const text = `${ds.label} ${formatted}`;
                            // eventはブラウザイベント (MouseEvent)
                            const e = event.native || event; // 互換
                            showLegendTooltip(text, e.clientX || 0, e.clientY || 0);
                        } catch (err) {
                            // noop
                        }
                    },
                    onLeave: function() {
                        hideLegendTooltip();
                    },
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
