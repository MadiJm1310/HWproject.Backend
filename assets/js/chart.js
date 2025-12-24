function createBarChart(canvasId, labels, values, labelText) {
    new Chart(document.getElementById(canvasId), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: labelText,
                data: values,
                backgroundColor: '#6A5ACD'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function createPieChart(canvasId, labels, values) {
    new Chart(document.getElementById(canvasId), {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    '#B5838D',
                    '#6D6875',
                    '#598392',
                    '#014F86',
                    '#9C6644',
                    '#FFAFCC'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false 
        }
    });
}

