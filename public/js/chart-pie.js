document.addEventListener("DOMContentLoaded", async function () {
    async function fetchPieChartData() {
        try {
            let response = await fetch('/chart-data');
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

            let data = await response.json();
            if (!Array.isArray(data.pie)) {
                throw new Error("Invalid data format: 'pie' should be an array");
            }

            return data.pie;
        } catch (error) {
            console.error('Error fetching pie chart data:', error);
            return [];
        }
    }

    let chartData = await fetchPieChartData();
    if (chartData.length === 0) return;

    var options = {
        series: chartData.map(item => item.value),
        chart: {
            width: 380,
            type: 'pie',
        },
        labels: chartData.map(item => item.name),
        colors: ["#4F46E5", "#10B981", "#F59E0B", "#DEE2E6"],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: { width: 300 },
                legend: { position: 'bottom' }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#apexcharts-pie"), options);
    chart.render();
});
