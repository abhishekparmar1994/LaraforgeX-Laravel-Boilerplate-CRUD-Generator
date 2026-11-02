{{-- ── DASHBOARD SCRIPTS & APEXCHARTS ENGINE ───────────────────── --}}
<script>
$(document).ready(async function () {
  try {
    const res = await axios.get('/admin/crm/analytics', { baseURL: '' });
    const data = res.data;

    // 1. Pipeline Stage Bar Chart
    if (document.querySelector("#pipeline-chart") && data.pipeline) {
      new ApexCharts(document.querySelector("#pipeline-chart"), {
        series: [{ name: 'Inquiries', data: data.pipeline.counts }],
        chart: { type: 'bar', height: 250, toolbar: { show: false }, fontFamily: 'inherit' },
        colors: ['#2b47ff'],
        plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
        dataLabels: { enabled: true, style: { fontSize: '10px', fontWeight: 700 } },
        xaxis: { categories: data.pipeline.labels, labels: { style: { fontSize: '11px', fontWeight: 600 } } },
        grid: { borderColor: '#f1f5f9' },
      }).render();
    }

    // 2. Revenue Area Trend Chart
    if (document.querySelector("#revenue-chart") && data.monthly_revenue) {
      new ApexCharts(document.querySelector("#revenue-chart"), {
        series: [{ name: 'Collected Revenue (₹)', data: data.monthly_revenue.totals }],
        chart: { type: 'area', height: 250, toolbar: { show: false }, fontFamily: 'inherit' },
        colors: ['#10b981'],
        stroke: { curve: 'smooth', width: 2.5 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 90, 100] } },
        xaxis: { categories: data.monthly_revenue.months, labels: { style: { fontSize: '10px' } } },
        yaxis: { labels: { formatter: val => '₹' + (val >= 100000 ? (val / 100000).toFixed(1) + 'L' : val) } },
        grid: { borderColor: '#f1f5f9' },
      }).render();
    }

    // 3. Solar Product Sector Donut Chart
    if (document.querySelector("#sector-chart") && data.sector_distribution) {
      new ApexCharts(document.querySelector("#sector-chart"), {
        series: data.sector_distribution.counts,
        labels: data.sector_distribution.labels,
        chart: { type: 'donut', height: 240, fontFamily: 'inherit' },
        colors: ['#2563eb', '#f59e0b', '#10b981', '#8b5cf6', '#06b6d4', '#f97316'],
        legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
        dataLabels: { enabled: true, formatter: val => Math.round(val) + '%' },
      }).render();
    }

    // 4. DISCOM Load Share Bar Chart
    if (document.querySelector("#discom-chart") && data.discom_distribution) {
      new ApexCharts(document.querySelector("#discom-chart"), {
        series: [{ name: 'Leads / Interconnections', data: data.discom_distribution.counts }],
        chart: { type: 'bar', height: 240, toolbar: { show: false }, fontFamily: 'inherit' },
        colors: ['#f59e0b'],
        plotOptions: { bar: { borderRadius: 6, horizontal: true, barHeight: '55%' } },
        dataLabels: { enabled: true, style: { fontSize: '10px', fontWeight: 700 } },
        xaxis: { categories: data.discom_distribution.labels, labels: { style: { fontSize: '11px' } } },
        grid: { borderColor: '#f1f5f9' },
      }).render();
    }

    // 5. Project Lifecycles Donut Chart
    if (document.querySelector("#project-status-chart") && data.project_statuses) {
      new ApexCharts(document.querySelector("#project-status-chart"), {
        series: data.project_statuses.counts,
        labels: data.project_statuses.labels,
        chart: { type: 'donut', height: 220, fontFamily: 'inherit' },
        colors: ['#64748b', '#8b5cf6', '#2b47ff', '#06b6d4', '#f59e0b', '#10b981', '#059669', '#ef4444'],
        legend: { position: 'bottom', fontSize: '10px' },
        dataLabels: { enabled: false }
      }).render();
    }

  } catch (err) {
    console.error("Dashboard charts telemetry error:", err);
  }
});

/**
 * Purpose: Complete a scheduled follow-up or site audit from the dashboard.
 * Parameters: string id
 */
async function completeFollowup(id) {
  try {
    await axios.post(`/admin/followups/${id}/complete`, {}, { baseURL: '' });
    if (window.showToast) {
      window.showToast('success', 'Site survey marked as completed!');
    }
    setTimeout(() => location.reload(), 300);
  } catch (e) {
    if (window.showToast) {
      window.showToast('error', 'Error updating site survey status.');
    } else {
      alert('Error updating follow-up');
    }
  }
}
</script>

