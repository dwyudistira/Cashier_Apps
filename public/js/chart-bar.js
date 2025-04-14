document.addEventListener("DOMContentLoaded", async function () {
    async function fetchBarChartData() {
        try {
            let response = await fetch('/chart-data');
            let data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching bar chart data:', error);
            return null;
        }   
    }

    let chartData = await fetchBarChartData();
    if (!chartData) return;

    const monthNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    const today = new Date();
    const currentMonth = today.getMonth();

    let labels = Object.keys(chartData.bar).map(day => {
        return `${day} ${monthNames[currentMonth]}`;
    });

    let ctx = document.getElementById("chartjs-bar").getContext("2d");

    if (window.barChart) {
        window.barChart.destroy();
    }

    window.barChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Jumlah Pembelian",
                backgroundColor: "#4F46E5",
                borderColor: "#4F46E5",
                hoverBackgroundColor: "#4F46E5",
                hoverBorderColor: "#4F46E5",
                data: Object.values(chartData.bar),
                barPercentage: 0.75,    
                categoryPercentage: 0.5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true }
            }
        }
    });
});
