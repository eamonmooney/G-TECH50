// JS FOR REPORTS PAGE - ALL GRAPHS
// BY SAFA

// Fetch php report data
async function fetchData(type) {
	const response = await fetch(`/G-TECH50/php/getReports.php?type=${type}`);
	return await response.json();
}

// Render the charts
async function renderCharts() {
	// Three diff charts
	const stockData = await fetchData('stock');
	const incomingData = await fetchData('incoming');
	const outgoingData = await fetchData('outgoing');
	const totalOrdersData = await fetchData('totalOrders');


	// Stock Chart
	new Chart(document.getElementById('stockChart'), {
		// Bar chart
		type: 'bar',
		// Labels from php
		data: {
			labels: stockData.map(p => p.ProductName),
			//   Data from php
			datasets: [{
				label: 'Stock',
				data: stockData.map(p => p.Stock),
				backgroundColor: 'rgba(75, 192, 192, 0.6)'
			}]
		},
		// Formatting
		options: {
			plugins: { legend: { display: false } },
			scales: { y: { beginAtZero: true } }
		}
	});



	// Incoming Orders Chart
	//   Group incoming by product name - like a map
	const incomingGrouped = {};
	//   For every incoming, group these with their quantity
	incomingData.forEach(i => {
		incomingGrouped[i.ProductName] = (incomingGrouped[i.ProductName] || 0) + parseInt(i.Quantity);
	});
	//   Chart
	new Chart(document.getElementById('incomingChart'), {
		// Bar chart
		type: 'bar',
		// Data labels from php
		data: {
			labels: Object.keys(incomingGrouped),
			// Datasets from php
			datasets: [{
				label: 'Incoming Qty',
				data: Object.values(incomingGrouped),
				backgroundColor: 'rgba(0, 200, 83, 0.6)'
			}]
		},
		// Formatting
		options: {
			plugins: { legend: { display: false } },
			scales: { y: { beginAtZero: true } }
		}
	});

	// Outgoing Orders Chart
	//   Group outgoing by product name - like a map
	const outgoingGrouped = {};
	//   For every outgoing, group these with their quantity
	outgoingData.forEach(i => {
		outgoingGrouped[i.ProductName] = (outgoingGrouped[i.ProductName] || 0) + parseInt(i.Quantity);
	});
	console.log('Outgoing data:', outgoingData);
	// Chart
	new Chart(document.getElementById('outgoingChart'), {
		// Bar chart
		type: 'bar',
		// Data labels from php
		data: {
			labels: Object.keys(outgoingGrouped),
			// Datasets from php
			datasets: [{
				label: 'Sent Qty',
				data: Object.values(outgoingGrouped),
				backgroundColor: 'rgba(255, 99, 132, 0.6)'
			}]
		},
		// Formatting
		options: {
			plugins: { legend: { display: false } },
			scales: { y: { beginAtZero: true } }
		}
	});

	// Chart for orders over time
	new Chart(document.getElementById('totalOrdersChart'), {
		// Line chart
		type: 'line',
		// Data labels from php

		data: {
			labels: totalOrdersData.map(o => o.OrderDay),
			datasets: [{
				label: 'Total Orders',
				// Datasets from php

				data: totalOrdersData.map(o => o.TotalOrders),
				backgroundColor: 'rgba(54, 162, 235, 0.2)',
				borderColor: 'rgba(54, 162, 235, 1)',
				borderWidth: 2,
				tension: 0.3,
				fill: true
			}]
		},
		// Formatting

		options: {
			scales: {
				y: { beginAtZero: true }
			}
		}
	});
}

// When loaded, render charts
document.addEventListener("DOMContentLoaded", renderCharts);