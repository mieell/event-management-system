(function () {
  const root = document.documentElement;
  const savedTheme = localStorage.getItem('Evenira-theme');
  if (savedTheme) root.setAttribute('data-theme', savedTheme);
  updateThemeIcon();

  window.addEventListener('load', () => {
    document.getElementById('preloader')?.classList.add('is-hidden');
  });

  document.querySelector('[data-sidebar-toggle]')?.addEventListener('click', () => {
    document.getElementById('sidebar')?.classList.toggle('is-open');
  });

  document.querySelector('[data-theme-toggle]')?.addEventListener('click', () => {
    const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-theme', next);
    localStorage.setItem('Evenira-theme', next);
    updateThemeIcon();
    window.EveniraCharts?.forEach((chart) => applyChartTheme(chart));
  });

  document.querySelectorAll('[data-counter]').forEach((node) => {
    const target = Number(node.dataset.counter || 0);
    const duration = 900;
    const start = performance.now();
    const tick = (now) => {
      const progress = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      node.textContent = Math.floor(target * eased).toLocaleString();
      if (progress < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  });

  document.querySelectorAll('[data-drop-zone]').forEach((zone) => {
    const input = zone.querySelector('input[type="file"]');
    const previewSelector = input?.dataset.preview;
    const preview = previewSelector ? document.querySelector(previewSelector) : null;

    ['dragenter', 'dragover'].forEach((eventName) => {
      zone.addEventListener(eventName, (event) => {
        event.preventDefault();
        zone.classList.add('is-dragging');
      });
    });

    ['dragleave', 'drop'].forEach((eventName) => {
      zone.addEventListener(eventName, (event) => {
        event.preventDefault();
        zone.classList.remove('is-dragging');
      });
    });

    zone.addEventListener('drop', (event) => {
      if (input && event.dataTransfer.files.length) {
        input.files = event.dataTransfer.files;
        renderPreview(input, preview);
      }
    });

    input?.addEventListener('change', () => renderPreview(input, preview));
  });

  function renderPreview(input, preview) {
    const file = input.files?.[0];
    if (!file || !preview || !file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = (event) => {
      preview.innerHTML = `<img src="${event.target.result}" alt="Upload preview">`;
    };
    reader.readAsDataURL(file);
  }

  document.querySelectorAll('[data-live-search]').forEach((form) => {
    let timer;
    form.querySelectorAll('input, select').forEach((field) => {
      field.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => form.submit(), 550);
      });
      field.addEventListener('change', () => {
        clearTimeout(timer);
        timer = setTimeout(() => form.submit(), 200);
      });
    });
  });

  if (window.Chart) {
    window.EveniraCharts = [];
    const monthlyNode = document.getElementById('monthlyChart');
    const monthlyDataNode = document.getElementById('monthlyChartData');
    if (monthlyNode && monthlyDataNode) {
      const rows = JSON.parse(monthlyDataNode.textContent || '[]');
      const chart = new Chart(monthlyNode, {
        type: 'line',
        data: {
          labels: rows.map((row) => row.month),
          datasets: [{
            label: 'Registrations',
            data: rows.map((row) => Number(row.total)),
            borderColor: cssVar('--cyan'),
            backgroundColor: chartFill(),
            fill: true,
            tension: .42,
            pointRadius: 4,
            pointBackgroundColor: cssVar('--sky')
          }]
        },
        options: chartOptions()
      });
      window.EveniraCharts.push(chart);
    }

    const categoryNode = document.getElementById('categoryChart');
    const categoryDataNode = document.getElementById('categoryChartData');
    if (categoryNode && categoryDataNode) {
      const rows = JSON.parse(categoryDataNode.textContent || '[]');
      const chart = new Chart(categoryNode, {
        type: 'doughnut',
        data: {
          labels: rows.map((row) => row.category),
          datasets: [{
            data: rows.map((row) => Number(row.total)),
            backgroundColor: chartPalette(),
            borderColor: 'rgba(255,255,255,.16)'
          }]
        },
        options: chartOptions(false)
      });
      window.EveniraCharts.push(chart);
    }
  }

  function chartOptions(showScales = true) {
    return {
      responsive: true,
      plugins: {
        legend: { labels: { color: cssVar('--text'), font: { family: 'Inter' } } }
      },
      scales: showScales ? {
        x: { ticks: { color: cssVar('--muted') }, grid: { color: 'rgba(56,189,248,.12)' } },
        y: { beginAtZero: true, ticks: { color: cssVar('--muted'), precision: 0 }, grid: { color: 'rgba(56,189,248,.12)' } }
      } : {}
    };
  }

  function updateThemeIcon() {
    const icon = document.querySelector('[data-theme-icon]');
    if (!icon) return;
    const isDark = root.getAttribute('data-theme') !== 'light';
    icon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
  }

  function cssVar(name) {
    return getComputedStyle(root).getPropertyValue(name).trim();
  }

  function chartFill() {
    return root.getAttribute('data-theme') === 'light'
      ? 'rgba(2,132,199,.14)'
      : 'rgba(56,189,248,.16)';
  }

  function chartPalette() {
    return [
      cssVar('--cyan'),
      '#7dd3fc',
      cssVar('--violet'),
      cssVar('--emerald'),
      cssVar('--amber'),
      cssVar('--danger')
    ];
  }

  function applyChartTheme(chart) {
    chart.options.plugins.legend.labels.color = cssVar('--text');
    if (chart.options.scales?.x) {
      chart.options.scales.x.ticks.color = cssVar('--muted');
      chart.options.scales.y.ticks.color = cssVar('--muted');
    }

    chart.data.datasets.forEach((dataset) => {
      if (chart.config.type === 'line') {
        dataset.borderColor = cssVar('--cyan');
        dataset.backgroundColor = chartFill();
        dataset.pointBackgroundColor = cssVar('--sky');
      }

      if (chart.config.type === 'doughnut') {
        dataset.backgroundColor = chartPalette();
      }
    });

    chart.update();
  }
})();
