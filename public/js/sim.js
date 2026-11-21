// Simulation Lab: seluruh perhitungan & animasi berjalan di browser (Canvas), tanpa request ke server saat Run.
(() => {
  const cv = document.getElementById('cv'), c = cv.getContext('2d'), W = cv.width, H = cv.height, $ = s => document.querySelector(s);
  const inputs = [...document.querySelectorAll('[data-p]')];
  const P = () => Object.fromEntries(inputs.map(i => [i.dataset.p, +i.value]));
  const fmt = v => typeof v !== 'number' ? v : (Math.abs(v) < .1 && v !== 0 ? +v.toPrecision(2) : Math.round(v * 100) / 100);
  const bg = () => {
    const g = c.createRadialGradient(W * .7, 0, 20, W / 2, H / 2, W * .8); g.addColorStop(0, 'rgba(58,51,153,.82)'); g.addColorStop(1, 'rgba(11,20,55,.9)');
    c.fillStyle = g; c.fillRect(0, 0, W, H); c.fillStyle = '#fff';
    for (let i = 0; i < 50; i++) { c.globalAlpha = .3 + .4 * Math.abs(Math.sin(i * 7 + performance.now() / 900 * (1 + i % 3))); c.fillRect((i * 97.3) % W, (i * 53.7) % H, 2, 2); }
    c.globalAlpha = 1;
  };
  const bubbles = (n, x0, x1, yb, h, now, col = '#fff') => {
    c.fillStyle = col;
    for (let i = 0; i < n; i++) { const ph = ((now * (.25 + (i % 5) * .08) + i * .37) % 1); c.globalAlpha = .8 * (1 - ph); c.beginPath(); c.arc(x0 + (x1 - x0) * ((Math.sin(i * 12.9) + 1) / 2) + Math.sin(now * 3 + i) * 6, yb - ph * h, 3 + (i % 4), 0, 7); c.fill(); }
    c.globalAlpha = 1;
  };
  const T = {
    projectile: {
      res: p => { const a = p.angle * Math.PI / 180, t = 2 * p.v * Math.sin(a) / p.g; return { 'Jangkauan (m)': p.v * Math.cos(a) * t, 'Tinggi maksimum (m)': (p.v * Math.sin(a)) ** 2 / (2 * p.g), 'Waktu di udara (s)': t }; },
      draw(p, k) {
        const a = p.angle * Math.PI / 180, vx = p.v * Math.cos(a), vy = p.v * Math.sin(a), tt = 2 * vy / p.g, R = vx * tt, Hm = vy * vy / (2 * p.g);
        const s = Math.min((W - 120) / Math.max(R, 1), (H - 130) / Math.max(Hm, 1)), ox = 60, oy = H - 50;
        c.fillStyle = '#2d8a5b'; c.fillRect(0, oy + 12, W, H); c.strokeStyle = '#a99cff'; c.setLineDash([6, 6]); c.lineWidth = 2; c.beginPath();
        for (let i = 0; i <= 60; i++) { const t = tt * i / 60; c[i ? 'lineTo' : 'moveTo'](ox + vx * t * s, oy - (vy * t - .5 * p.g * t * t) * s); } c.stroke(); c.setLineDash([]);
        const t = tt * k; c.fillStyle = '#ffd36b'; c.shadowColor = '#ffd36b'; c.shadowBlur = 16; c.beginPath(); c.arc(ox + vx * t * s, oy - Math.max(0, vy * t - .5 * p.g * t * t) * s, 11, 0, 7); c.fill(); c.shadowBlur = 0;
        c.fillStyle = '#fff'; c.beginPath(); c.moveTo(ox - 14, oy + 12); c.lineTo(ox + 14, oy + 12); c.lineTo(ox, oy - 8); c.fill();
      }
    },
    reaction: {
      res: p => { const r = p.conc / 50 * 2 ** ((p.temp - 25) / 10); return { 'Laju reaksi relatif (x)': r, 'Waktu selesai (s)': 60 / r }; },
      draw(p, k, now, run) {
        const r = p.conc / 50 * 2 ** ((p.temp - 25) / 10), hot = (p.temp - 10) / 70;
        c.save(); c.beginPath(); c.moveTo(335, 40); c.lineTo(425, 40); c.lineTo(425, 150); c.lineTo(540, 360); c.quadraticCurveTo(550, 385, 520, 385); c.lineTo(240, 385); c.quadraticCurveTo(210, 385, 220, 360); c.lineTo(335, 150); c.closePath();
        c.fillStyle = 'rgba(255,255,255,.12)'; c.fill(); c.clip();
        c.fillStyle = `hsl(${265 - hot * 90},75%,${55 + p.conc / 10}%)`; c.globalAlpha = .85; c.fillRect(200, 250 - (run ? 0 : 0), 400, 150); c.globalAlpha = 1;
        bubbles(run ? Math.min(40, 6 + r * 8) : 0, 270, 490, 380, 170, now, '#fff'); c.restore();
        c.strokeStyle = '#cbbfff'; c.lineWidth = 4; c.beginPath(); c.moveTo(335, 40); c.lineTo(335, 150); c.lineTo(220, 360); c.quadraticCurveTo(210, 385, 240, 385); c.lineTo(520, 385); c.quadraticCurveTo(550, 385, 540, 360); c.lineTo(425, 150); c.lineTo(425, 40); c.stroke();
      }
    },
    orbit: {
      res: p => ({ 'Periode orbit (tahun)': Math.sqrt(p.radius ** 3 / p.mass), 'Kecepatan orbit (km/s)': 29.78 * Math.sqrt(p.mass / p.radius) }),
      draw(p, k, now, run) {
        const cx = W / 2, cy = H / 2, R = 30 + p.radius / 5 * 160, per = Math.sqrt(p.radius ** 3 / p.mass);
        c.strokeStyle = 'rgba(200,190,255,.5)'; c.setLineDash([5, 7]); c.beginPath(); c.arc(cx, cy, R, 0, 7); c.stroke(); c.setLineDash([]);
        c.fillStyle = '#ffc94d'; c.shadowColor = '#ffb300'; c.shadowBlur = 40; c.beginPath(); c.arc(cx, cy, 16 + p.mass * 7, 0, 7); c.fill(); c.shadowBlur = 0;
        const a = run ? now / (per * 2) * 2 * Math.PI : 0; c.fillStyle = '#8ec5ff'; c.beginPath(); c.arc(cx + R * Math.cos(a), cy + R * Math.sin(a), 12, 0, 7); c.fill();
      }
    },
    photosynthesis: {
      res: p => { const r = Math.min(100, Math.min(p.light, p.co2 * 1.5)); return { 'Laju fotosintesis (%)': r, 'Oksigen (mL/menit)': r * .12 }; },
      draw(p, k, now, run) {
        const r = Math.min(100, Math.min(p.light, p.co2 * 1.5)), cx = 380, cy = 270;
        c.fillStyle = `rgba(255,214,90,${.4 + p.light / 170})`; c.shadowColor = '#ffd36b'; c.shadowBlur = p.light; c.beginPath(); c.arc(90, 80, 36, 0, 7); c.fill(); c.shadowBlur = 0;
        c.strokeStyle = `rgba(255,214,90,${p.light / 160})`; c.lineWidth = 3; for (let i = 0; i < 8; i++) { const a = i * Math.PI / 4 + now * .3; c.beginPath(); c.moveTo(90 + Math.cos(a) * 46, 80 + Math.sin(a) * 46); c.lineTo(90 + Math.cos(a) * (56 + p.light / 6), 80 + Math.sin(a) * (56 + p.light / 6)); c.stroke(); }
        c.fillStyle = '#3cb36b'; c.beginPath(); c.ellipse(cx, cy, 150, 70, -.3, 0, 7); c.fill(); c.strokeStyle = '#d7ffd9'; c.lineWidth = 3; c.beginPath(); c.moveTo(cx - 130, cy + 38); c.lineTo(cx + 130, cy - 38); c.stroke();
        c.fillStyle = 'rgba(200,220,255,.8)'; c.font = '14px Poppins'; for (let i = 0; i < p.co2 / 8; i++) { const ph = (now * .15 + i * .21) % 1; c.fillText('CO₂', 560 - ph * 180, 130 + (i % 5) * 28 + ph * 80); }
        bubbles(run ? Math.round(r / 5) : 0, cx - 90, cx + 90, cy - 20, 150, now, '#bff3ff');
      }
    },
    wave: {
      res: p => ({ 'Periode (s)': 1 / p.freq, 'Panjang gelombang (m)': 4 / p.freq, 'Amplitudo': p.amp }),
      draw(p, k, now, run) {
        c.strokeStyle = 'rgba(255,255,255,.2)'; c.lineWidth = 1; c.beginPath(); c.moveTo(0, H / 2); c.lineTo(W, H / 2); c.stroke();
        c.strokeStyle = '#a99cff'; c.lineWidth = 4; c.shadowColor = '#8b7df0'; c.shadowBlur = 14; c.beginPath();
        for (let x = 0; x <= W; x += 3) { const y = H / 2 - p.amp * 18 * Math.sin(2 * Math.PI * (p.freq * x / W * 3 - (run ? now * p.freq * .6 : 0))); c[x ? 'lineTo' : 'moveTo'](x, y); } c.stroke(); c.shadowBlur = 0;
      }
    }
  };
  Object.assign(T, typeof window.SIMX === 'function' ? window.SIMX({ c, W, H }) : {});
  const sim = T[window.SIM.type] || T.wave;
  let run = false, t0 = 0, result = null;
  const frame = now => { bg(); sim.draw(P(), run ? Math.min(1, (now - t0) / 4000) : 0, now / 1000, run); requestAnimationFrame(frame); };
  requestAnimationFrame(frame);

  const paint = i => i.style.setProperty('--p', ((i.value - i.min) / (i.max - i.min) * 100) + '%');
  inputs.forEach(i => { paint(i); i.addEventListener('input', () => { paint(i); const t = i.value + i.dataset.unit; $('#o-' + i.dataset.p).textContent = t; i.closest('.slider').querySelector('small').textContent = t; }); });
  $('#run').addEventListener('click', () => {
    run = true; t0 = performance.now(); result = Object.fromEntries(Object.entries(sim.res(P())).map(([k, v]) => [k, fmt(v)]));
    $('#results').innerHTML = Object.entries(result).map(([k, v], i) => `<div style="animation-delay:${i * .1}s"><b>${v}</b><span>${k}</span></div>`).join('');
    $('#resbox').hidden = false;
  });
  $('#reset').addEventListener('click', () => { inputs.forEach(i => { i.value = i.dataset.def; i.dispatchEvent(new Event('input')); }); run = false; result = null; $('#resbox').hidden = true; });
  const save = $('#save'), modal = $('#modal');
  if (save && modal) {
    save.addEventListener('click', () => {
      const par = Object.fromEntries(inputs.map(i => [i.dataset.label, +i.value]));
      $('#f-par').value = JSON.stringify(par); $('#f-res').value = JSON.stringify(result);
      $('#sum').innerHTML = Object.entries(result).map(([k, v]) => `<div><b>${v}</b><span>${k}</span></div>`).join('');
      modal.classList.add('open');
    });
    $('#cancel').addEventListener('click', () => modal.classList.remove('open'));
    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });
  }
})();
