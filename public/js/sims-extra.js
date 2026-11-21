// 50 simulasi tambahan (10 per bidang). Dipanggil dari sim.js: window.SIMX({c, W, H}) -> { tipe: {res(p), draw(p, k, t, run)} }
window.SIMX = ({ c, W, H }) => {
  const PI = Math.PI, L = 70, R = W - 30, Tp = 40, B = H - 70, f2 = v => Math.round(v * 100) / 100, cl = (v, a, b) => Math.min(b, Math.max(a, v));
  const line = (a, b, x, y, col = '#fff', w = 2) => { c.strokeStyle = col; c.lineWidth = w; c.beginPath(); c.moveTo(a, b); c.lineTo(x, y); c.stroke(); };
  const circ = (x, y, r, fill, stroke) => { c.beginPath(); c.arc(x, y, Math.max(0, r), 0, 7); if (fill) { c.fillStyle = fill; c.fill(); } if (stroke) { c.strokeStyle = stroke; c.lineWidth = 2; c.stroke(); } };
  const dot = (x, y, col = '#ffd36b', r = 8) => { c.shadowColor = col; c.shadowBlur = 14; circ(x, y, r, col); c.shadowBlur = 0; };
  const txt = (s, x, y, col = '#fff', sz = 14, al = 'center') => { c.fillStyle = col; c.font = sz + 'px Inter,sans-serif'; c.textAlign = al; c.fillText(s, x, y); };
  const rect = (x, y, w, h, fill, stroke) => { if (fill) { c.fillStyle = fill; c.fillRect(x, y, w, h); } if (stroke) { c.strokeStyle = stroke; c.lineWidth = 2; c.strokeRect(x, y, w, h); } };
  const tri = u => Math.abs((((u % 2) + 2) % 2) - 1);
  const box = (x0, x1, y0, y1, xl = '', yl = '') => {
    const X = x => L + (x - x0) / (x1 - x0) * (R - L), Y = y => B - (y - y0) / (y1 - y0) * (B - Tp);
    for (let i = 0; i <= 5; i++) { const x = x0 + (x1 - x0) * i / 5, y = y0 + (y1 - y0) * i / 5;
      line(X(x), Tp, X(x), B, 'rgba(255,255,255,.16)', 1); line(L, Y(y), R, Y(y), 'rgba(255,255,255,.16)', 1);
      txt(+x.toFixed(2), X(x), B + 16, '#cfd0ff', 12); txt(+y.toFixed(2), L - 8, Y(y) + 4, '#cfd0ff', 12, 'right'); }
    if (y0 < 0 && y1 > 0) line(L, Y(0), R, Y(0), 'rgba(255,255,255,.55)', 1.5);
    if (x0 < 0 && x1 > 0) line(X(0), Tp, X(0), B, 'rgba(255,255,255,.55)', 1.5);
    line(L, B, R, B, 'rgba(255,255,255,.6)', 2); line(L, Tp, L, B, 'rgba(255,255,255,.6)', 2);
    txt(xl, (L + R) / 2, B + 38, '#e8e6ff', 13);
    c.save(); c.translate(18, (Tp + B) / 2); c.rotate(-PI / 2); txt(yl, 0, 0, '#e8e6ff', 13); c.restore();
    return { X, Y };
  };
  const plot = (f, x0, x1, m, col = '#a99cff', w = 3, n = 240) => {
    c.save(); c.beginPath(); c.rect(L, Tp, R - L, B - Tp); c.clip(); c.strokeStyle = col; c.lineWidth = w; c.beginPath(); let st = false;
    for (let i = 0; i <= n; i++) { const x = x0 + (x1 - x0) * i / n, y = f(x); if (!isFinite(y)) { st = false; continue; } st ? c.lineTo(m.X(x), m.Y(y)) : c.moveTo(m.X(x), m.Y(y)); st = true; }
    c.stroke(); c.restore();
  };
  const bars = (v, lb, mx, cols = ['#8b7df0']) => {
    const n = v.length, w = (R - L) / n * .6;
    v.forEach((y, i) => { const x = L + (R - L) * (i + .5) / n, h = Math.max(0, y) / mx * (B - Tp - 20); rect(x - w / 2, B - h, w, h, cols[i % cols.length]); txt(+y.toFixed(2), x, B - h - 8, '#fff', 13); txt(lb[i], x, B + 18, '#cfd0ff', 13); });
    line(L, B, R, B, 'rgba(255,255,255,.5)', 1.5);
  };
  const erf = x => { const s = x < 0 ? -1 : 1, a = Math.abs(x), t = 1 / (1 + .3275911 * a); return s * (1 - (((((1.061405429 * t - 1.453152027) * t) + 1.421413741) * t - .284496736) * t + .254829592) * t * Math.exp(-a * a)); };
  const shells = z => z <= 2 ? [z] : z <= 10 ? [2, z - 2] : z <= 18 ? [2, 8, z - 10] : z <= 20 ? [2, 8, 8, z - 18] : z <= 30 ? [2, 8, z - 12, 2] : [2, 8, 18, z - 28];
  const kep = (M, e) => { let E = M; for (let i = 0; i < 8; i++) E = M + e * Math.sin(E); return E; };
  const enz = (T, h) => 100 * Math.exp(-(((T - 37) / 12) ** 2)) * Math.exp(-(((h - 7) / 2) ** 2));
  const trp = p => (p.temp / 25) * (1.05 - p.humidity / 100) * (1 + p.wind / 10) * 50;
  const lv = p => { let x = 40, y = 9; const X = [], Y = []; for (let i = 0; i <= 6000; i++) { if (i % 30 === 0) { X.push(x); Y.push(y); } x += (p.a * x - p.b * x * y) * .01; y += (-p.d * y + p.b * .5 * x * y) * .01; } return [X, Y]; };
  const lg = (p, t) => p.K / (1 + (p.K - p.N0) / p.N0 * Math.exp(-p.r * t));
  const phAt = (p, vb) => { const nA = p.Ma * 25, nB = p.Mb * vb, V = 25 + vb; return nA > nB + 1e-9 ? -Math.log10((nA - nB) / V) : nB > nA + 1e-9 ? 14 + Math.log10((nB - nA) / V) : 7; };
  const sol = T => 13.3 + .561 * T + .01766 * T * T;
  const kp = T => .15 * Math.exp(-(57200 / 8.314) * (1 / T - 1 / 298));
  const dayLen = (lat, d, tilt) => { const dec = tilt * Math.sin(2 * PI * (d - 80) / 365) * PI / 180, x = -Math.tan(lat * PI / 180) * Math.tan(dec); return x >= 1 ? 0 : x <= -1 ? 24 : 24 / PI * Math.acos(x); };
  const PL = ['Merkurius', 'Venus', 'Bumi', 'Mars', 'Jupiter', 'Saturnus', 'Uranus', 'Neptunus'], GP = [3.7, 8.87, 9.81, 3.71, 24.79, 10.44, 8.87, 11.15];
  const OB = ['Bulan', 'Mars (terdekat)', 'Matahari', 'Jupiter (terdekat)', 'Saturnus (terdekat)', 'Neptunus (terdekat)'], OD = [.384, 54.6, 149.6, 588, 1200, 4300];
  const FS = ['Bulan Baru', 'Sabit Awal', 'Kuartal Pertama', 'Cembung Awal', 'Purnama', 'Cembung Akhir', 'Kuartal Akhir', 'Sabit Akhir'];
  const quadR = p => { const D = p.b * p.b - 4 * p.a * p.c; return Math.abs(p.a) < .05 || D < 0 ? [] : [(-p.b - Math.sqrt(D)) / (2 * p.a), (-p.b + Math.sqrt(D)) / (2 * p.a)]; };
  const TF = p => { const th = p.rot * PI / 180; return [[1, 1], [3, 1], [1, 3]].map(([x, y]) => [(x * Math.cos(th) - y * Math.sin(th)) * p.k + p.tx, (x * Math.sin(th) + y * Math.cos(th)) * p.k + p.ty]); };
  let probSim = null;

  return {
    // ================= BIOLOGI =================
    transp: { res: p => ({ 'Indeks transpirasi (relatif)': trp(p) }),
      draw(p, k, t, run) { const n = Math.min(40, Math.round(trp(p) / 4)); c.fillStyle = '#3cb36b'; c.beginPath(); c.ellipse(W / 2, H / 2 + 120, 250, 70, 0, 0, 7); c.fill();
        for (let i = 0; i < 9; i++) circ(W / 2 - 200 + i * 50, H / 2 + 120, 7, '#1f7a46');
        for (let i = 0; i < n; i++) { const ph = ((run ? t * .35 : 0) + i * .173) % 1; circ(W / 2 - 200 + (i % 9) * 50 + Math.sin(i + t) * 6 * (p.wind / 10), H / 2 + 110 - ph * 220, 4, 'rgba(160,215,255,' + (1 - ph) + ')'); }
        for (let i = 0; i < Math.round(p.wind / 2); i++) line(30, 90 + i * 34, 90 + p.wind * 5, 90 + i * 34, 'rgba(255,255,255,.5)', 2);
        txt('Uap air keluar lewat stomata', W / 2, 40, '#cfd0ff', 15); txt('Indeks = ' + f2(trp(p)), W / 2, H - 30, '#ffd36b', 18); } },
    enzyme: { res: p => ({ 'Aktivitas enzim (%)': enz(p.temp, p.ph) }),
      draw(p) { const m = box(0, 80, 0, 100, 'Suhu (°C)', 'Aktivitas relatif (%)'); plot(T => enz(T, p.ph), 0, 80, m); dot(m.X(p.temp), m.Y(enz(p.temp, p.ph))); txt('pH = ' + p.ph + ' (optimum: suhu 37 °C, pH 7)', W / 2, 26, '#cfd0ff'); } },
    respiration: { res: p => ({ 'Detak jantung (bpm)': 70 + p.intensity / 10 * (220 - p.age - 70), 'Laju napas (kali/menit)': 14 + p.intensity * 3.2, 'Denyut maksimum (bpm)': 220 - p.age }),
      draw(p, k, t, run) { const hr = 70 + p.intensity / 10 * (220 - p.age - 70), beat = run ? Math.abs(Math.sin(t * PI * hr / 60)) : 0;
        circ(W / 2, 150, 50 + beat * 14, 'rgba(255,90,120,.85)'); txt('♥', W / 2, 165, '#fff', 44); txt(Math.round(hr) + ' bpm', W / 2, 250, '#fff', 22);
        c.strokeStyle = '#7dffb0'; c.lineWidth = 2.5; c.beginPath();
        for (let x = 0; x <= W; x += 3) { const ph = (((x / 90) * hr / 60 - (run ? t * hr / 60 : 0)) % 1 + 1) % 1, s = ph < .08 ? 70 * Math.sin(ph / .08 * PI) : ph > .18 && ph < .3 ? 20 * Math.sin((ph - .18) / .12 * PI) : 0, y = H - 120 - s; x ? c.lineTo(x, y) : c.moveTo(x, y); }
        c.stroke(); } },
    osmosis: { res: p => { const d = (0.9 - p.ext) * 35; return { 'Perubahan volume sel (%)': d, 'Kondisi': Math.abs(p.ext - .9) < .05 ? 'Isotonik' : p.ext < .9 ? 'Hipotonik (sel membengkak)' : 'Hipertonik (sel mengerut)' }; },
      draw(p, k, t, run) { const d = (.9 - p.ext) * 35, r = 90 * (1 + d / 100); circ(W / 2, H / 2, 160, 'rgba(120,180,255,' + (.08 + p.ext / 6) + ')'); circ(W / 2, H / 2, r, 'rgba(160,140,255,.6)', '#fff');
        txt('Sel (0,9%)', W / 2, H / 2 + 5, '#fff', 16); txt('Larutan luar ' + p.ext + '%', W / 2, H / 2 + 195, '#cfd0ff', 15);
        if (Math.abs(d) > 1) for (let i = 0; i < 10; i++) { const a = i * PI / 5, ph = ((run ? t * .6 : 0) + i * .1) % 1, rr = d > 0 ? r + (160 - r) * (1 - ph) : r + (160 - r) * ph; circ(W / 2 + Math.cos(a) * rr, H / 2 + Math.sin(a) * rr, 5, '#9fd4ff'); } } },
    cell: { res: p => ({ 'Luas permukaan (µm²)': 4 * PI * p.r ** 2, 'Volume (µm³)': 4 / 3 * PI * p.r ** 3, 'Rasio luas/volume (1/µm)': 3 / p.r }),
      draw(p) { const r2 = 15 + p.r * 3.8; circ(W / 2, H / 2, r2, 'rgba(160,140,255,.55)', '#fff'); circ(W / 2, H / 2, r2 * .35, '#7c6cf0'); txt('r = ' + p.r + ' µm', W / 2, H / 2 - r2 - 14, '#fff', 16); txt('Luas/Volume = ' + f2(3 / p.r) + ' (makin kecil sel, makin besar rasionya)', W / 2, H - 36, '#ffd36b', 15); } },
    dna: { res: p => ({ 'Suhu leleh Tm (°C)': 64.9 + 41 * (p.gc / 100 * p.len - 16.4) / p.len, 'Jumlah ikatan hidrogen': p.len * ((1 - p.gc / 100) * 2 + p.gc / 100 * 3), 'Pasangan G–C': Math.round(p.len * p.gc / 100) }),
      draw(p, k, t, run) { for (let i = 0; i < 40; i++) { const x = 40 + i * 17, ph = i * .42 + (run ? t * 2 : 0), y1 = H / 2 + Math.sin(ph) * 70, y2 = H / 2 - Math.sin(ph) * 70, gc = (i * 37) % 100 < p.gc;
        line(x, y1, x, y2, gc ? '#ffd36b' : '#8bc7ff', gc ? 4 : 2); circ(x, y1, 6, '#a99cff'); circ(x, y2, 6, '#59a5ff'); }
        txt('Kuning = pasangan G–C (3 ikatan H) · Biru = A–T (2 ikatan H)', W / 2, H - 30, '#cfd0ff', 13); } },
    hardy: { res: p => ({ 'AA (%)': p.p * p.p * 100, 'Aa (%)': 2 * p.p * (1 - p.p) * 100, 'aa (%)': (1 - p.p) ** 2 * 100 }),
      draw(p) { const q = 1 - p.p; bars([p.p * p.p * 100, 2 * p.p * q * 100, q * q * 100], ['AA', 'Aa', 'aa'], 100, ['#8b7df0', '#59a5ff', '#ffd36b']); txt('Frekuensi alel A (p) = ' + p.p + ' · alel a (q) = ' + f2(q), W / 2, 28, '#fff', 15); } },
    logistic: { res: p => ({ 'Populasi pada t = 20': lg(p, 20), 'Waktu mencapai K/2': p.N0 < p.K / 2 ? Math.log((p.K - p.N0) / p.N0) / p.r : 0 }),
      draw(p, k, t, run) { const m = box(0, 20, 0, p.K * 1.1, 'Waktu', 'Populasi'); plot(x => lg(p, x), 0, 20, m); c.setLineDash([6, 6]); line(L, m.Y(p.K), R, m.Y(p.K), '#ffd36b', 1); c.setLineDash([]); txt('K = ' + p.K, R - 40, m.Y(p.K) - 8, '#ffd36b', 13); dot(m.X(20 * k), m.Y(lg(p, 20 * k))); } },
    food: { res: p => { const f = p.eff / 100; return { 'Produsen (kJ)': p.energy, 'Konsumen I (kJ)': p.energy * f, 'Konsumen II (kJ)': p.energy * f * f, 'Konsumen III (kJ)': p.energy * f ** 3 }; },
      draw(p) { const f = p.eff / 100; bars([p.energy, p.energy * f, p.energy * f * f, p.energy * f ** 3], ['Produsen', 'Konsumen I', 'Konsumen II', 'Konsumen III'], p.energy, ['#59d08a', '#8b7df0', '#59a5ff', '#ffd36b']); txt('Energi tersisa di tiap tingkat trofik (efisiensi ' + p.eff + '%)', W / 2, 28, '#fff', 15); } },
    predprey: { res: p => { const [X, Y] = lv(p); return { 'Puncak populasi mangsa': Math.max(...X), 'Puncak populasi predator': Math.max(...Y) }; },
      draw(p, k, t, run) { const [X, Y] = lv(p), mx = Math.max(...X, ...Y) * 1.1, m = box(0, 60, 0, mx, 'Waktu', 'Populasi');
        [[X, '#59d08a', 'Mangsa'], [Y, '#ff7a90', 'Predator']].forEach(([S, col, nm], j) => { const n = run && k < 1 ? Math.max(2, Math.ceil(S.length * k)) : S.length; c.strokeStyle = col; c.lineWidth = 3; c.beginPath(); for (let i = 0; i < n; i++) { const x = m.X(i * .3), y = m.Y(S[i]); i ? c.lineTo(x, y) : c.moveTo(x, y); } c.stroke(); txt(nm, R - 60, Tp + 18 + j * 20, col, 14, 'right'); }); } },

    // ================= FISIKA =================
    freefall: { res: p => ({ 'Waktu jatuh (s)': Math.sqrt(2 * p.h / p.g), 'Kecepatan di tanah (m/s)': Math.sqrt(2 * p.g * p.h) }),
      draw(p, k) { const tf = Math.sqrt(2 * p.h / p.g), tt = k * tf, fy = .5 * p.g * tt * tt / p.h; line(W / 2 - 120, 60, W / 2 + 120, 60, '#fff', 3); rect(W / 2 - 160, H - 70, 320, 8, '#59d08a'); line(W / 2 + 100, 60, W / 2 + 100, H - 70, 'rgba(255,255,255,.35)', 1); txt(p.h + ' m', W / 2 + 140, H / 2, '#cfd0ff'); const y = 60 + fy * (H - 140); dot(W / 2, y, '#ffd36b', 16); txt('t = ' + f2(tt) + ' s', W / 2 - 80, y + 5, '#fff'); } },
    circular: { res: p => ({ 'Percepatan sentripetal (m/s²)': p.v * p.v / p.r, 'Gaya sentripetal (N)': p.m * p.v * p.v / p.r, 'Periode (s)': 2 * PI * p.r / p.v }),
      draw(p, k, t, run) { const Rp = 40 + p.r * 32, a = (run ? t : 0) * Math.min(p.v / p.r, 8), cx = W / 2, cy = H / 2, x = cx + Rp * Math.cos(a), y = cy + Rp * Math.sin(a); circ(cx, cy, Rp, null, 'rgba(255,255,255,.3)'); dot(cx, cy, '#ffd36b', 6); line(cx, cy, x, y, 'rgba(255,255,255,.3)', 1); dot(x, y, '#8b7df0', 14); line(x, y, x + (cx - x) * .35, y + (cy - y) * .35, '#ff7a90', 3); txt('gaya sentripetal (merah)', cx, cy + Rp + 30, '#ff7a90', 13); } },
    pendulum: { res: p => { const T = 2 * PI * Math.sqrt(p.L / p.g); return { 'Periode (s)': T, 'Frekuensi (Hz)': 1 / T }; },
      draw(p, k, t, run) { const T = 2 * PI * Math.sqrt(p.L / p.g), a = p.a0 * PI / 180 * (run ? Math.cos(2 * PI * t / T) : 1), len = 60 + p.L * 110, ox = W / 2, oy = 60; line(ox - 60, oy, ox + 60, oy, '#fff', 4); const bx = ox + len * Math.sin(a), by = oy + len * Math.cos(a); line(ox, oy, bx, by, '#cfd0ff', 2); dot(bx, by, '#ffd36b', 18); txt('L = ' + p.L + ' m', ox + 100, oy + 30, '#cfd0ff'); txt('Berlaku untuk simpangan kecil', W / 2, H - 30, '#cfd0ff', 13); } },
    spring: { res: p => ({ 'Periode (s)': 2 * PI * Math.sqrt(p.m / p.k), 'Energi total (J)': .5 * p.k * p.A * p.A, 'Kecepatan maksimum (m/s)': p.A * Math.sqrt(p.k / p.m) }),
      draw(p, k, t, run) { const T = 2 * PI * Math.sqrt(p.m / p.k), x = p.A * (run ? Math.cos(2 * PI * t / T) : 1), mx = 380 + x * 450; line(60, H / 2 - 60, 60, H / 2 + 60, '#fff', 6);
        c.strokeStyle = '#cfd0ff'; c.lineWidth = 3; c.beginPath(); c.moveTo(60, H / 2); for (let i = 1; i <= 14; i++) c.lineTo(60 + (mx - 90) * i / 14, H / 2 + (i % 2 ? -18 : 18)); c.lineTo(mx - 30, H / 2); c.stroke();
        rect(mx - 30, H / 2 - 30, 60, 60, '#8b7df0'); txt('m = ' + p.m + ' kg', mx, H / 2 + 55); line(380, H / 2 + 80, 380, H / 2 + 100, '#ffd36b', 2); txt('titik setimbang', 380, H / 2 + 118, '#ffd36b', 12); } },
    refraction: { res: p => { const s = p.n1 * Math.sin(p.theta1 * PI / 180) / p.n2; return { 'Sudut bias (°)': s > 1 ? '—' : Math.asin(s) * 180 / PI, 'Sudut kritis (°)': p.n1 > p.n2 ? Math.asin(p.n2 / p.n1) * 180 / PI : '—', 'Status': s > 1 ? 'Pemantulan sempurna' : 'Cahaya dibiaskan' }; },
      draw(p, k, t, run) { const cx = W / 2, cy = H / 2, t1 = p.theta1 * PI / 180, s = p.n1 * Math.sin(t1) / p.n2, Lr = 220; rect(0, 0, W, cy, 'rgba(120,160,255,' + p.n1 / 8 + ')'); rect(0, cy, W, cy, 'rgba(120,160,255,' + p.n2 / 8 + ')');
        c.setLineDash([6, 6]); line(cx, 20, cx, H - 20, 'rgba(255,255,255,.6)', 1); c.setLineDash([]); line(0, cy, W, cy, '#fff', 2);
        line(cx - Lr * Math.sin(t1), cy - Lr * Math.cos(t1), cx, cy, '#ffd36b', 3);
        if (s > 1) line(cx, cy, cx + Lr * Math.sin(t1), cy - Lr * Math.cos(t1), '#ff7a90', 3); else { const t2 = Math.asin(s); line(cx, cy, cx + Lr * Math.sin(t2), cy + Lr * Math.cos(t2), '#7dffb0', 3); }
        txt('n₁ = ' + p.n1, 60, cy - 14, '#fff'); txt('n₂ = ' + p.n2, 60, cy + 28, '#fff'); } },
    incline: { res: p => { const th = p.angle * PI / 180, a = 9.8 * (Math.sin(th) - p.mu * Math.cos(th)); return { 'Percepatan (m/s²)': Math.max(0, a), 'Gaya normal (N)': p.mass * 9.8 * Math.cos(th), 'Status': a > 0 ? 'Meluncur' : 'Diam (gesekan cukup besar)' }; },
      draw(p, k) { const th = p.angle * PI / 180, a = Math.max(0, 9.8 * (Math.sin(th) - p.mu * Math.cos(th))), Lr = 520, x0 = 90, y0 = H - 70, tt = k * 4;
        c.fillStyle = 'rgba(255,255,255,.14)'; c.beginPath(); c.moveTo(x0, y0); c.lineTo(x0 + Lr * Math.cos(th), y0); c.lineTo(x0 + Lr * Math.cos(th), y0 - Lr * Math.sin(th)); c.closePath(); c.fill(); line(x0, y0, x0 + Lr * Math.cos(th), y0 - Lr * Math.sin(th), '#fff', 3);
        const u = Math.max(.08, .88 - .5 * a * tt * tt * .015); c.save(); c.translate(x0 + u * Lr * Math.cos(th), y0 - u * Lr * Math.sin(th)); c.rotate(-th); rect(-25, -50, 50, 50, '#ffd36b'); c.restore(); txt('θ = ' + p.angle + '°', x0 + 80, y0 - 10, '#cfd0ff'); } },
    collision: { res: p => { const v1 = (p.m1 - p.e * p.m2) / (p.m1 + p.m2) * p.v1, v2 = (1 + p.e) * p.m1 / (p.m1 + p.m2) * p.v1; return { "Kecepatan benda 1 setelah (m/s)": v1, "Kecepatan benda 2 setelah (m/s)": v2, 'Energi kinetik hilang (%)': 100 * (1 - (p.m1 * v1 * v1 + p.m2 * v2 * v2) / (p.m1 * p.v1 * p.v1)) }; },
      draw(p, k) { const v1 = (p.m1 - p.e * p.m2) / (p.m1 + p.m2) * p.v1, v2 = (1 + p.e) * p.m1 / (p.m1 + p.m2) * p.v1, w1 = 30 + p.m1 * 4, w2 = 30 + p.m2 * 4, y = H / 2 + 40, hit = .4;
        const x1 = k < hit ? 60 + 240 * (k / hit) : 300 + v1 * (k - hit) * 22, x2 = 400 + (k < hit ? 0 : v2 * (k - hit) * 22); line(20, y, W - 20, y, '#fff', 3);
        rect(cl(x1, 20, W - 60) - w1 / 2, y - w1, w1, w1, '#8b7df0'); rect(cl(x2, 20, W - 40) - w2 / 2, y - w2, w2, w2, '#59a5ff'); txt('m₁ = ' + p.m1 + ' kg', cl(x1, 60, W - 60), y + 26, '#fff'); txt('m₂ = ' + p.m2 + ' kg', cl(x2, 60, W - 60), y + 46, '#fff'); txt('e = ' + p.e + (p.e >= 1 ? ' (lenting sempurna)' : p.e <= 0 ? ' (tidak lenting)' : ''), W / 2, 60, '#ffd36b', 16); } },
    buoyancy: { res: p => { const f = Math.min(1, p.rhob / p.rhof); return { 'Bagian tercelup (%)': f * 100, 'Gaya apung (N)': p.rhof * 9.8 * .001 * f, 'Berat benda (N)': p.rhob * 9.8 * .001, 'Status': p.rhob < p.rhof ? 'Mengapung' : p.rhob === p.rhof ? 'Melayang' : 'Tenggelam' }; },
      draw(p, k, t, run) { const wl = H / 2, f = Math.min(1, p.rhob / p.rhof), bs = 120, top = p.rhob < p.rhof ? wl - (1 - f) * bs : H - 40 - bs; const bob = run && p.rhob < p.rhof ? Math.sin(t * 3) * 4 : 0;
        rect(0, wl, W, H - wl, 'rgba(80,150,255,.35)'); line(0, wl, W, wl, '#9fd4ff', 2); rect(W / 2 - bs / 2, top + bob, bs, bs, '#ffd36b');
        line(W / 2, top + bs / 2, W / 2, top + bs / 2 + 70, '#ff7a90', 4); txt('berat', W / 2 + 40, top + bs / 2 + 70, '#ff7a90', 13); if (p.rhob <= p.rhof) { line(W / 2 + 50, top + bs, W / 2 + 50, top + bs - 60 * f - 10, '#7dffb0', 4); txt('apung', W / 2 + 90, top + bs - 40, '#7dffb0', 13); } txt('ρ benda = ' + p.rhob + ' · ρ fluida = ' + p.rhof + ' kg/m³', W / 2, 40, '#cfd0ff', 15); } },
    ohm: { res: p => { const I = p.V / (p.R1 + p.R2); return { 'Arus (A)': I, 'Tegangan R1 (V)': I * p.R1, 'Tegangan R2 (V)': I * p.R2, 'Daya (W)': p.V * I }; },
      draw(p, k, t, run) { const I = p.V / (p.R1 + p.R2), x0 = 150, y0 = 130, w = 460, h = 250; c.strokeStyle = '#cfd0ff'; c.lineWidth = 3; c.strokeRect(x0, y0, w, h);
        [[x0 + 90, y0], [x0 + w - 190, y0]].forEach(([x, y], i) => { rect(x, y - 14, 100, 28, '#161a55', '#cfd0ff'); txt('R' + (i + 1) + ' = ' + p['R' + (i + 1)] + ' Ω', x + 50, y - 24, '#ffd36b', 13); });
        rect(x0 - 14, y0 + h / 2 - 30, 28, 60, '#161a55', '#cfd0ff'); txt('+', x0 - 30, y0 + h / 2 - 8, '#ff7a90', 22); txt(p.V + ' V', x0 - 55, y0 + h / 2 + 34, '#fff');
        for (let i = 0; i < 24; i++) { const d = ((i / 24 + (run ? t * Math.min(I, 3) * .06 : 0)) % 1) * 2 * (w + h); let x, y; if (d < w) { x = x0 + d; y = y0; } else if (d < w + h) { x = x0 + w; y = y0 + d - w; } else if (d < 2 * w + h) { x = x0 + w - (d - w - h); y = y0 + h; } else { x = x0; y = y0 + h - (d - 2 * w - h); } circ(x, y, 4, '#59d0ff'); }
        txt('I = ' + f2(I) + ' A', W / 2, y0 + h / 2 + 8, '#fff', 22); } },
    gas: { res: p => ({ 'Tekanan (kPa)': p.n * 8.314 * p.T / p.V, 'Kecepatan rms N₂ (m/s)': Math.sqrt(3 * 8.314 * p.T / .028) }),
      draw(p, k, t, run) { const w = 60 + p.V * 45, h = 300, x0 = (W - w) / 2, y0 = 110, N = Math.max(3, Math.round(p.n * 30)), sp = Math.sqrt(p.T) / 18, tt = run ? t : 0; c.strokeStyle = '#fff'; c.lineWidth = 3; c.strokeRect(x0, y0, w, h);
        for (let i = 0; i < N; i++) { const a = i * 2.4; circ(x0 + 8 + tri(i * .37 + tt * sp * Math.cos(a) * .5) * (w - 16), y0 + 8 + tri(i * .61 + tt * sp * Math.sin(a) * .5) * (h - 16), 4, 'hsl(' + (200 + p.T / 3) + ',90%,65%)'); }
        txt('V = ' + p.V + ' L · T = ' + p.T + ' K · n = ' + p.n + ' mol', W / 2, y0 + h + 40, '#cfd0ff', 14); } },

    // ================= KIMIA =================
    decay: { res: p => { const N = p.N0 * .5 ** (p.t / p.half); return { 'Inti tersisa': N, 'Persentase tersisa (%)': N / p.N0 * 100, 'Jumlah waktu paruh': p.t / p.half }; },
      draw(p) { const m = box(0, 200, 0, p.N0 * 1.05, 'Waktu (hari)', 'Jumlah inti'), N = p.N0 * .5 ** (p.t / p.half); plot(x => p.N0 * .5 ** (x / p.half), 0, 200, m); dot(m.X(p.t), m.Y(N)); const lit = Math.round(N / p.N0 * 100);
        for (let i = 0; i < 100; i++) circ(R - 120 + (i % 10) * 11, Tp + 14 + Math.floor(i / 10) * 11, 4, i < lit ? '#7dffb0' : 'rgba(255,255,255,.15)'); txt('Hijau = belum meluruh', R - 70, Tp + 135, '#cfd0ff', 11); } },
    config: { res: p => { const s = shells(p.Z); return { 'Konfigurasi kulit (K, L, M, N)': s.join(', '), 'Elektron kulit terluar': s[s.length - 1] }; },
      draw(p, k, t, run) { const s = shells(p.Z), cx = W / 2, cy = H / 2; s.forEach((n, i) => { const r = 55 + i * 42; circ(cx, cy, r, null, 'rgba(255,255,255,.3)'); for (let j = 0; j < n; j++) { const a = 2 * PI * j / n + (run ? t * (.7 - i * .12) : 0); dot(cx + r * Math.cos(a), cy + r * Math.sin(a), i === s.length - 1 ? '#ffd36b' : '#59a5ff', 6); } });
        circ(cx, cy, 26, '#7c6cf0'); txt('Z = ' + p.Z, cx, cy + 5, '#fff', 14); txt('Konfigurasi: ' + s.join(', ') + ' (tanpa pengecualian Cr dan Cu)', W / 2, H - 24, '#cfd0ff', 13); } },
    ph: { res: p => ({ 'pH': p.ph, '[H⁺] (mol/L)': (10 ** (-p.ph)).toExponential(2), '[OH⁻] (mol/L)': (10 ** (p.ph - 14)).toExponential(2), 'Sifat': p.ph < 6.95 ? 'Asam' : p.ph > 7.05 ? 'Basa' : 'Netral' }),
      draw(p) { const hue = x => x < 7 ? x / 7 * 120 : 120 + (x - 7) / 7 * 150, bw = (W - 140) / 14; for (let i = 0; i < 14; i++) { rect(70 + i * bw, 70, bw, 40, 'hsl(' + hue(i + .5) + ',80%,50%)'); txt(i, 70 + (i + .5) * bw, 130, '#cfd0ff', 12); }
        const mx = 70 + p.ph / 14 * (W - 140); c.fillStyle = '#fff'; c.beginPath(); c.moveTo(mx, 66); c.lineTo(mx - 9, 48); c.lineTo(mx + 9, 48); c.fill(); txt('pH ' + p.ph, mx, 40, '#fff', 15);
        c.fillStyle = 'hsl(' + hue(p.ph) + ',80%,50%)'; c.beginPath(); c.moveTo(W / 2 - 110, 200); c.lineTo(W / 2 - 110, 420); c.lineTo(W / 2 + 110, 420); c.lineTo(W / 2 + 110, 200); c.lineTo(W / 2 + 100, 200); c.lineTo(W / 2 + 100, 410); c.lineTo(W / 2 - 100, 410); c.lineTo(W / 2 - 100, 200); c.fill(); rect(W / 2 - 100, 260, 200, 150, 'hsla(' + hue(p.ph) + ',80%,50%,.55)');
        txt('Indikator universal', W / 2, 450, '#cfd0ff', 14); } },
    titration: { res: p => ({ 'pH campuran': phAt(p, p.Vb), 'Volume titik ekuivalen (mL)': p.Ma * 25 / p.Mb, 'Status': Math.abs(p.Ma * 25 - p.Mb * p.Vb) < .05 ? 'Titik ekuivalen' : p.Mb * p.Vb < p.Ma * 25 ? 'Masih asam' : 'Kelebihan basa' }),
      draw(p) { const m = box(0, 60, 0, 14, 'Volume NaOH ditambahkan (mL)', 'pH'); plot(v => phAt(p, v), 0, 60, m, '#a99cff', 3, 600); c.setLineDash([6, 6]); const ve = p.Ma * 25 / p.Mb; line(m.X(ve), Tp, m.X(ve), B, '#ffd36b', 1); c.setLineDash([]); txt('ekuivalen ' + f2(ve) + ' mL', m.X(ve) + 6, Tp + 14, '#ffd36b', 12, 'left'); dot(m.X(p.Vb), m.Y(phAt(p, p.Vb))); txt('25 mL HCl ' + p.Ma + ' M dititrasi NaOH ' + p.Mb + ' M', W / 2, 26, '#cfd0ff', 14); } },
    molarity: { res: p => { const mol = p.g / p.Mr; return { 'Jumlah mol (mol)': mol, 'Molaritas (M)': mol / (p.V / 1000) }; },
      draw(p) { const mol = p.g / p.Mr, M = mol / (p.V / 1000), a = Math.min(.95, M / 2 + .08), x0 = W / 2 - 110; rect(x0, 130, 220, 280, 'rgba(255,255,255,.06)', '#fff'); rect(x0 + 2, 150, 216, 258, 'rgba(124,108,240,' + a + ')');
        for (let i = 0; i < Math.min(80, Math.round(mol * 40)); i++) circ(x0 + 14 + (i * 53 % 190), 165 + (i * 37 % 230), 3, '#ffd36b'); txt('M = ' + f2(M) + ' mol/L', W / 2, 450, '#ffd36b', 18); txt(p.g + ' g zat (Mr ' + p.Mr + ') dalam ' + p.V + ' mL', W / 2, 90, '#cfd0ff', 14); } },
    equilibrium: { res: p => { const K = kp(p.T); return { 'Tetapan Kp (atm)': K, 'Derajat disosiasi N₂O₄ (%)': Math.sqrt(K / (K + 4 * p.P)) * 100 }; },
      draw(p, k, t, run) { const K = kp(p.T), al = Math.sqrt(K / (K + 4 * p.P)), nA = Math.round(24 * (1 - al)), nB = Math.round(48 * al), x0 = 120, y0 = 90, w = W - 240, h = 320; c.strokeStyle = '#fff'; c.lineWidth = 3; c.strokeRect(x0, y0, w, h);
        for (let i = 0; i < nA + nB; i++) { const A = i < nA, sp = Math.sqrt(p.T) / 20, a = i * 2.1; circ(x0 + 10 + tri(i * .43 + (run ? t : 0) * sp * Math.cos(a) * .5) * (w - 20), y0 + 10 + tri(i * .71 + (run ? t : 0) * sp * Math.sin(a) * .5) * (h - 20), A ? 9 : 5, A ? '#59a5ff' : '#ff9a4d'); }
        txt('● N₂O₄ (biru)  ⇌  2 NO₂ (oranye)', W / 2, 50, '#fff', 15); txt('T = ' + p.T + ' K · P = ' + p.P + ' atm · α = ' + f2(al * 100) + '%', W / 2, y0 + h + 40, '#ffd36b', 15); } },
    electro: { res: p => { const E = Math.abs(p.e1 - p.e2); return { 'Potensial sel (V)': E, 'Anode (E° lebih rendah)': Math.min(p.e1, p.e2), 'Katode (E° lebih tinggi)': Math.max(p.e1, p.e2), 'Reaksi spontan?': E > 0 ? 'Ya' : 'Tidak (E sel = 0)' }; },
      draw(p, k, t, run) { const E = Math.abs(p.e1 - p.e2), lo = Math.min(p.e1, p.e2), hi = Math.max(p.e1, p.e2); [[200, 'Anode', lo], [W - 200, 'Katode', hi]].forEach(([x, nm, e]) => { rect(x - 80, 270, 160, 150, 'rgba(120,160,255,.3)', '#fff'); rect(x - 8, 220, 16, 150, nm === 'Anode' ? '#ff9a4d' : '#59d08a'); txt(nm, x, 445, '#fff', 14); txt('E° = ' + e + ' V', x, 465, '#cfd0ff', 13); });
        line(200, 220, 200, 130, '#cfd0ff', 3); line(200, 130, W - 200, 130, '#cfd0ff', 3); line(W - 200, 130, W - 200, 220, '#cfd0ff', 3); line(280, 340, W - 280, 340, 'rgba(255,255,255,.5)', 3);
        circ(W / 2, 130, 26, 'rgba(255,211,107,' + cl(E / 3, .08, 1) + ')', '#ffd36b'); txt('E sel = ' + f2(E) + ' V', W / 2, 80, '#ffd36b', 18);
        for (let i = 0; i < 12; i++) { const s = ((i / 12) + (run && E > 0 ? t * .25 : 0)) % 1, d = s * (90 + (W - 400) + 90); let x, y; if (d < 90) { x = 200; y = 220 - d; } else if (d < 90 + W - 400) { x = 200 + d - 90; y = 130; } else { x = W - 200; y = 130 + d - 90 - (W - 400); } circ(x, y, 4, '#59d0ff'); } } },
    solubility: { res: p => { const s = sol(p.T), ex = Math.max(0, p.mass - s); return { 'Kelarutan KNO₃ (g/100 g air)': s, 'Endapan (g)': ex, 'Status': ex > 0 ? 'Lewat jenuh (ada endapan)' : Math.abs(p.mass - s) < 2 ? 'Tepat jenuh' : 'Belum jenuh' }; },
      draw(p) { const m = box(0, 100, 0, 260, 'Suhu (°C)', 'g KNO₃ / 100 g air'); plot(sol, 0, 100, m); const ok = p.mass <= sol(p.T); dot(m.X(p.T), m.Y(p.mass), ok ? '#7dffb0' : '#ff7a90'); txt(ok ? 'Di bawah kurva: larut semua' : 'Di atas kurva: ada endapan', W / 2, 26, ok ? '#7dffb0' : '#ff7a90', 15); } },
    stoich: { res: p => { const w = Math.min(p.nh, 2 * p.no); return { 'Pereaksi pembatas': p.nh < 2 * p.no ? 'H₂' : p.nh > 2 * p.no ? 'O₂' : 'Habis bersamaan', 'H₂O terbentuk (mol)': w, 'Sisa pereaksi (mol)': p.nh < 2 * p.no ? p.no - p.nh / 2 : p.nh - 2 * p.no }; },
      draw(p) { const w = Math.min(p.nh, 2 * p.no), left = p.nh < 2 * p.no ? p.no - p.nh / 2 : p.nh - 2 * p.no; bars([p.nh, p.no, w, left], ['H₂ awal', 'O₂ awal', 'H₂O terbentuk', 'Sisa'], Math.max(p.nh, p.no, w, left) * 1.1, ['#59a5ff', '#59d08a', '#a99cff', '#ffd36b']); txt('2 H₂ + O₂ → 2 H₂O', W / 2, 28, '#fff', 16); } },
    calorimetry: { res: p => { const Tf = (p.m * p.c * p.Tm + 418 * 25) / (p.m * p.c + 418); return { 'Suhu akhir (°C)': Tf, 'Kalor diterima air (J)': 418 * (Tf - 25) }; },
      draw(p, k, t, run) { const Tf = (p.m * p.c * p.Tm + 418 * 25) / (p.m * p.c + 418), Tw = 25 + (Tf - 25) * (run ? k : 0), hue = T => 210 - cl((T - 25) / 175, 0, 1) * 210; rect(120, 150, 220, 250, 'rgba(255,255,255,.06)', '#fff'); rect(122, 230, 216, 168, 'hsla(' + hue(Tw) + ',80%,55%,.6)');
        rect(190, 160, 80, 60, 'hsl(' + hue(run ? Tw + (p.Tm - Tw) * (1 - k) : p.Tm) + ',80%,55%)', '#fff'); txt('Logam ' + p.m + ' g · ' + p.Tm + ' °C', 230, 140, '#cfd0ff', 13); txt('Air 100 g · 25 °C', 230, 425, '#cfd0ff', 13);
        rect(470, 120, 30, 300, 'rgba(255,255,255,.12)', '#fff'); const hh = cl(Tw / 200, 0, 1) * 300; rect(472, 420 - hh, 26, hh, '#ff7a90'); txt(f2(Tw) + ' °C', 485, 105, '#ffd36b', 16); } },

    // ================= ASTRONOMI =================
    kepler2: { res: p => ({ 'Perihelion (AU)': p.a * (1 - p.e), 'Aphelion (AU)': p.a * (1 + p.e), 'Periode (tahun)': p.a ** 1.5, 'Rasio kecepatan perihelion/aphelion': (1 + p.e) / (1 - p.e) }),
      draw(p, k, t, run) { const sc = 220 / p.a, cx = W / 2, cy = H / 2, b = p.a * Math.sqrt(1 - p.e * p.e), sx = cx + p.a * p.e * sc, pos = M => { const E = kep(M, p.e); return [sx + sc * p.a * (Math.cos(E) - p.e), cy + sc * b * Math.sin(E)]; };
        c.strokeStyle = 'rgba(255,255,255,.35)'; c.lineWidth = 1.5; c.beginPath(); c.ellipse(cx, cy, p.a * sc, b * sc, 0, 0, 7); c.stroke(); const M = (run ? t : 0) * 2 * PI / 8 + 1;
        c.fillStyle = 'rgba(255,211,107,.45)'; c.beginPath(); c.moveTo(sx, cy); for (let j = 0; j <= 12; j++) { const [x, y] = pos(M - .6 * (1 - j / 12)); c.lineTo(x, y); } c.closePath(); c.fill();
        dot(sx, cy, '#ffc94d', 14); const [px, py] = pos(M); dot(px, py, '#8ec5ff', 10); txt('Luas yang disapu sama dalam waktu yang sama', W / 2, 30, '#cfd0ff', 14); } },
    escape: { res: p => ({ 'Kecepatan lepas (km/s)': 11.19 * Math.sqrt(p.mass / p.rad), 'Percepatan gravitasi (m/s²)': 9.81 * p.mass / p.rad ** 2 }),
      draw(p, k, t, run) { const r = 30 + p.rad * 14, cx = W / 2, cy = H - 20; circ(cx, cy, r, '#4a6fd8', '#9fd4ff'); const h = run ? (t * 60) % 200 : 0; rect(cx - 6, cy - r - 40 - h, 12, 34, '#fff'); c.fillStyle = '#ff7a90'; c.beginPath(); c.moveTo(cx - 6, cy - r - 6 - h); c.lineTo(cx + 6, cy - r - 6 - h); c.lineTo(cx, cy - r + 8 - h); c.fill();
        txt('v lepas = ' + f2(11.19 * Math.sqrt(p.mass / p.rad)) + ' km/s', W / 2, 60, '#ffd36b', 20); txt('M = ' + p.mass + ' M⊕ · R = ' + p.rad + ' R⊕ (Bumi: 11,19 km/s)', W / 2, 90, '#cfd0ff', 14); } },
    gravity: { res: p => ({ 'Planet': PL[p.planet - 1], 'Gravitasi (m/s²)': GP[p.planet - 1], 'Berat di planet (N)': p.mass * GP[p.planet - 1], 'Berat di Bumi (N)': p.mass * 9.81 }),
      draw(p) { const g = GP[p.planet - 1]; bars([p.mass * 9.81, p.mass * g], ['Di Bumi', 'Di ' + PL[p.planet - 1]], Math.max(p.mass * 9.81, p.mass * g) * 1.1, ['#59a5ff', '#ffd36b']); txt('Massamu tetap ' + p.mass + ' kg, tetapi beratmu berubah', W / 2, 30, '#fff', 15); } },
    lightt: { res: p => { const d = OD[p.obj - 1], tl = d * 1e6 / 299792 / 60; return { 'Objek': OB[p.obj - 1], 'Jarak (juta km)': d, 'Waktu tempuh cahaya (menit)': tl, 'Waktu tempuh pesawat (menit)': tl / p.frac }; },
      draw(p, k) { const d = OD[p.obj - 1]; line(110, H / 2, W - 110, H / 2, 'rgba(255,255,255,.3)', 2); circ(110, H / 2, 24, '#4a6fd8'); txt('Bumi', 110, H / 2 + 50, '#fff'); circ(W - 110, H / 2, 20, '#ffc94d'); txt(OB[p.obj - 1], W - 110, H / 2 + 50, '#fff');
        dot(110 + (W - 220) * k, H / 2, '#ffd36b', 8); dot(110 + (W - 220) * k * Math.min(1, p.frac), H / 2 + 30, '#ff7a90', 8); txt('cahaya (kuning)', W / 2, H / 2 - 40, '#ffd36b', 13); txt('pesawat ' + p.frac + ' c (merah)', W / 2, H / 2 + 80, '#ff7a90', 13); txt(d + ' juta km', W / 2, 60, '#cfd0ff', 16); } },
    star: { res: p => { const m = p.M + 5 * Math.log10(p.d / 10); return { 'Magnitudo semu': m, 'Terlihat mata telanjang?': m <= 6 ? 'Ya' : 'Tidak', 'Kecerahan relatif thd bintang mag. 6': 10 ** (.4 * (6 - m)) }; },
      draw(p) { const m = p.M + 5 * Math.log10(p.d / 10), rr = cl(70 - m * 4, 3, 140), g = c.createRadialGradient(W / 2, H / 2, 0, W / 2, H / 2, rr); g.addColorStop(0, '#fff'); g.addColorStop(.3, 'rgba(255,240,180,.8)'); g.addColorStop(1, 'rgba(255,240,180,0)'); circ(W / 2, H / 2, rr, g);
        c.setLineDash([5, 6]); circ(W / 2, H / 2, 46, null, 'rgba(255,255,255,.5)'); c.setLineDash([]); txt('lingkaran putus-putus = ukuran bintang magnitudo 6 (batas mata)', W / 2, H - 30, '#cfd0ff', 13); txt('m = ' + f2(m), W / 2, 50, '#ffd36b', 20); } },
    phases: { res: p => { const a = p.angle * PI / 180; return { 'Iluminasi (%)': (1 - Math.cos(a)) / 2 * 100, 'Fase': FS[Math.round(p.angle / 45) % 8] }; },
      draw(p, k, t, run) { const A = (p.angle + (run ? k * 360 : 0)) * PI / 180, ex = W / 2, ey = 150, mx = ex - 100 * Math.cos(A), my = ey - 100 * Math.sin(A); line(20, ey, 70, ey, '#ffd36b', 3); txt('Sinar Matahari →', 90, ey - 14, '#ffd36b', 12); circ(ex, ey, 26, '#4a6fd8'); c.setLineDash([4, 5]); circ(ex, ey, 100, null, 'rgba(255,255,255,.3)'); c.setLineDash([]); circ(mx, my, 12, '#eee');
        const mcx = W / 2, mcy = H - 110, r = 70, ca = Math.cos(A), wax = Math.sin(A) > 0; circ(mcx, mcy, r, '#1c2050', 'rgba(255,255,255,.3)'); c.fillStyle = '#f1ecd8';
        for (let y = -r; y <= r; y++) { const w = Math.sqrt(r * r - y * y); wax ? c.fillRect(mcx + ca * w, mcy + y, w - ca * w, 1.2) : c.fillRect(mcx - w, mcy + y, w + ca * w, 1.2); } } },
    season: { res: p => ({ 'Deklinasi Matahari (°)': p.tilt * Math.sin(2 * PI * (p.day - 80) / 365), 'Panjang siang (jam)': dayLen(p.lat, p.day, p.tilt) }),
      draw(p) { const m = box(1, 365, 0, 24, 'Hari ke-', 'Panjang siang (jam)'); plot(d => dayLen(p.lat, d, p.tilt), 1, 365, m); dot(m.X(p.day), m.Y(dayLen(p.lat, p.day, p.tilt))); txt('Lintang ' + p.lat + '° · kemiringan sumbu ' + p.tilt + '°', W / 2, 26, '#cfd0ff', 14); } },
    tides: { res: p => { const Am = 1 / (p.dist / 100) ** 3, As = .46, a = p.ang * PI / 180, H2 = Math.sqrt(Am * Am + As * As + 2 * Am * As * Math.cos(2 * a)), cs = Math.cos(2 * a); return { 'Tinggi pasang relatif': H2, 'Jenis pasang': cs > .8 ? 'Pasang purnama (spring)' : cs < -.8 ? 'Pasang perbani (neap)' : 'Peralihan' }; },
      draw(p, k, t, run) { const Am = 1 / (p.dist / 100) ** 3, a = (p.ang + (run ? k * 360 : 0)) * PI / 180, Hh = Math.sqrt(Am * Am + .46 ** 2 + 2 * Am * .46 * Math.cos(2 * a)), cx = W / 2, cy = H / 2 + 10, mx = cx - 190 * Math.cos(a), my = cy - 190 * Math.sin(a);
        line(20, cy, 70, cy, '#ffd36b', 3); txt('Matahari →', 70, cy - 14, '#ffd36b', 12); c.save(); c.translate(cx, cy); c.rotate(Math.atan2(my - cy, mx - cx)); c.fillStyle = 'rgba(80,150,255,.45)'; c.beginPath(); c.ellipse(0, 0, 85 + Hh * 40, 85 - Hh * 12 + 8, 0, 0, 7); c.fill(); c.restore(); circ(cx, cy, 70, '#4a6fd8'); circ(mx, my, 14, '#eee'); txt('tinggi pasang relatif = ' + f2(Hh), W / 2, H - 30, '#ffd36b', 16); } },
    hubble: { res: p => { const v = p.H0 * p.d, b = v / 299792, z = Math.sqrt((1 + b) / (1 - b)) - 1; return { 'Kecepatan menjauh (km/s)': v, 'Pergeseran merah z': z, 'Persen kecepatan cahaya (%)': b * 100 }; },
      draw(p, k, t, run) { const cx = W / 2, cy = H / 2; dot(cx, cy, '#59a5ff', 12); txt('Kita', cx, cy + 30, '#fff', 13); for (let i = 0; i < 8; i++) { const a = i * PI / 4 + .3, r0 = 40 + (i % 3) * 55 + 30, r = r0 * (1 + (run ? (t * .08) % .5 : 0)), x = cx + r * Math.cos(a), y = cy + r * Math.sin(a); circ(x, y, 7, '#ffd36b'); line(x, y, x + Math.cos(a) * r * p.H0 / 400, y + Math.sin(a) * r * p.H0 / 400, '#ff7a90', 2); }
        txt('Semakin jauh galaksi, semakin cepat menjauh (v = H₀ × d)', W / 2, 36, '#cfd0ff', 14); txt('d = ' + p.d + ' Mpc → v = ' + Math.round(p.H0 * p.d) + ' km/s', W / 2, H - 30, '#ffd36b', 16); } },
    telescope: { res: p => ({ 'Perbesaran (kali)': p.fo / p.fe, 'Daya pisah (detik busur)': 116 / p.D, 'Pengumpulan cahaya (× mata, pupil 7 mm)': (p.D / 7) ** 2 }),
      draw(p) { const hh = 10 + p.D * .1, ox = 100, fx = ox + p.fo * .24, ey = H / 2; rect(ox, ey - hh - 6, p.fo * .24 + p.fe * 6 + 20, 6, '#555'); rect(ox, ey + hh, p.fo * .24 + p.fe * 6 + 20, 6, '#555');
        c.fillStyle = 'rgba(160,210,255,.6)'; c.beginPath(); c.ellipse(ox, ey, 8, hh, 0, 0, 7); c.fill(); c.beginPath(); c.ellipse(fx + p.fe * 6, ey, 5, hh * .5, 0, 0, 7); c.fill();
        [-.7, 0, .7].forEach(f => { line(20, ey + f * hh, ox, ey + f * hh, '#ffd36b', 2); line(ox, ey + f * hh, fx, ey, '#ffd36b', 2); line(fx, ey, fx + p.fe * 6, ey + f * hh * .5, '#ffd36b', 2); line(fx + p.fe * 6, ey + f * hh * .5, fx + p.fe * 6 + 60, ey + f * hh * .5, '#ffd36b', 2); });
        txt('M = ' + f2(p.fo / p.fe) + '×', W / 2, 60, '#fff', 22); txt('objektif ' + p.fo + ' mm · okuler ' + p.fe + ' mm · diameter ' + p.D + ' mm', W / 2, H - 40, '#cfd0ff', 14); } },

    // ================= MATEMATIKA =================
    trig: { res: p => { const a = p.angle * PI / 180, cs = Math.cos(a); return { 'sin θ': Math.sin(a), 'cos θ': cs, 'tan θ': Math.abs(cs) < 1e-9 ? 'tak terdefinisi' : Math.tan(a) }; },
      draw(p, k, t, run) { const A = (p.angle + (run ? k * 360 : 0)) * PI / 180, cx = 230, cy = H / 2, r = 150, x = cx + r * Math.cos(A), y = cy - r * Math.sin(A); circ(cx, cy, r, null, 'rgba(255,255,255,.4)'); line(cx - r - 20, cy, cx + r + 20, cy, 'rgba(255,255,255,.4)', 1); line(cx, cy - r - 20, cx, cy + r + 20, 'rgba(255,255,255,.4)', 1);
        line(cx, cy, x, y, '#fff', 2); line(x, y, x, cy, '#ff7a90', 3); line(cx, cy, x, cy, '#59a5ff', 3); dot(x, y, '#ffd36b', 7); txt('sin', x + 24, (y + cy) / 2, '#ff7a90', 13); txt('cos', (x + cx) / 2, cy + 22, '#59a5ff', 13);
        c.strokeStyle = '#a99cff'; c.lineWidth = 3; c.beginPath(); for (let i = 0; i <= 360; i += 4) { const px = 450 + i / 360 * 290, py = cy - Math.sin(i * PI / 180) * 100; i ? c.lineTo(px, py) : c.moveTo(px, py); } c.stroke(); line(450, cy, 740, cy, 'rgba(255,255,255,.4)', 1);
        const deg = ((A * 180 / PI) % 360 + 360) % 360; dot(450 + deg / 360 * 290, cy - Math.sin(A) * 100, '#ffd36b', 7); txt('θ = ' + Math.round(deg) + '°', cx, 50, '#fff', 18); txt('grafik y = sin θ', 595, cy + 140, '#cfd0ff', 13); } },
    riemann: { res: p => { const dx = p.b / p.n; let s = 0; for (let i = 0; i < p.n; i++) s += (i * dx) ** 2 * dx; const ex = p.b ** 3 / 3; return { 'Jumlah Riemann kiri': s, 'Nilai eksak ∫x² dx': ex, 'Galat': Math.abs(ex - s) }; },
      draw(p) { const m = box(0, p.b, 0, p.b * p.b * 1.1, 'x', 'f(x) = x²'), dx = p.b / p.n; for (let i = 0; i < p.n; i++) { const x = i * dx, h = x * x; rect(m.X(x), m.Y(h), m.X(x + dx) - m.X(x), m.Y(0) - m.Y(h), 'rgba(139,125,240,.45)', p.n <= 40 ? 'rgba(255,255,255,.6)' : null); } plot(x => x * x, 0, p.b, m, '#ffd36b'); } },
    linear: { res: p => ({ 'Persamaan': 'y = ' + p.m + 'x ' + (p.c < 0 ? '− ' : '+ ') + Math.abs(p.c), 'Titik potong sumbu-y': p.c, 'Titik potong sumbu-x': p.m === 0 ? 'tidak ada' : -p.c / p.m }),
      draw(p) { const m = box(-10, 10, -10, 10, 'x', 'y'); plot(x => p.m * x + p.c, -10, 10, m); dot(m.X(0), m.Y(p.c)); if (p.m !== 0 && Math.abs(p.c / p.m) <= 10) dot(m.X(-p.c / p.m), m.Y(0), '#7dffb0'); txt('y = ' + p.m + 'x ' + (p.c < 0 ? '− ' : '+ ') + Math.abs(p.c) + ' (gradien ' + p.m + ')', W / 2, 26, '#cfd0ff', 15); } },
    quad: { res: p => { const r = quadR(p), D = p.b * p.b - 4 * p.a * p.c; return { 'Titik puncak': Math.abs(p.a) < .05 ? '—' : '(' + f2(-p.b / (2 * p.a)) + ', ' + f2(p.c - p.b * p.b / (4 * p.a)) + ')', 'Diskriminan': D, 'Akar-akar': r.length ? r.map(f2).join(' dan ') : 'tidak ada akar real' }; },
      draw(p) { const m = box(-8, 8, -10, 10, 'x', 'y'); plot(x => p.a * x * x + p.b * x + p.c, -8, 8, m); if (Math.abs(p.a) >= .05) dot(m.X(-p.b / (2 * p.a)), m.Y(p.c - p.b * p.b / (4 * p.a)), '#ffd36b'); quadR(p).forEach(x => dot(m.X(x), m.Y(0), '#7dffb0', 6)); txt('y = ' + p.a + 'x² + ' + p.b + 'x + ' + p.c, W / 2, 26, '#cfd0ff', 15); } },
    expo: { res: p => ({ 'a^x': p.a ** p.x, 'log_a(a^x) = x (fungsi invers)': Math.log(p.a ** p.x) / Math.log(p.a) }),
      draw(p) { const m = box(-3, 6, -3, 6, 'x', 'y'); plot(x => p.a ** x, -3, 6, m, '#59d08a'); plot(x => Math.log(x) / Math.log(p.a), .001, 6, m, '#ff7a90'); c.setLineDash([6, 6]); plot(x => x, -3, 6, m, 'rgba(255,255,255,.5)', 1.5); c.setLineDash([]);
        dot(m.X(p.x), m.Y(p.a ** p.x), '#ffd36b', 6); txt('hijau: y = ' + p.a + '^x · merah: y = log x (basis ' + p.a + ') · putus-putus: y = x', W / 2, 26, '#cfd0ff', 13); } },
    seq: { res: p => ({ 'Suku ke-n (Un)': p.a1 + (p.n - 1) * p.d, 'Jumlah n suku (Sn)': p.n / 2 * (2 * p.a1 + (p.n - 1) * p.d) }),
      draw(p) { const v = [], lb = []; for (let i = 1; i <= p.n; i++) { v.push(p.a1 + (i - 1) * p.d); lb.push(p.n <= 12 || i % 5 === 0 ? i : ''); } bars(v, lb, Math.max(...v) * 1.1 || 1, ['#8b7df0', '#59a5ff']); txt('Barisan aritmetika: a = ' + p.a1 + ', beda = ' + p.d, W / 2, 28, '#fff', 15); } },
    pytha: { res: p => ({ 'Sisi miring c': Math.hypot(p.a, p.b), 'a²': p.a * p.a, 'b²': p.b * p.b, 'c²': p.a * p.a + p.b * p.b }),
      draw(p) { const sc = 12, ox = 300, oy = 330, A = [ox + p.a * sc, oy], Bp = [ox, oy - p.b * sc], n = [p.b * sc, -p.a * sc];
        const sq = (pts, fill) => { c.fillStyle = fill; c.beginPath(); pts.forEach(([x, y], i) => i ? c.lineTo(x, y) : c.moveTo(x, y)); c.closePath(); c.fill(); c.strokeStyle = '#fff'; c.lineWidth = 1.5; c.stroke(); };
        sq([[ox, oy], A, [A[0], oy + p.a * sc], [ox, oy + p.a * sc]], 'rgba(89,165,255,.5)'); sq([[ox, oy], Bp, [ox - p.b * sc, Bp[1]], [ox - p.b * sc, oy]], 'rgba(89,208,138,.5)'); sq([A, Bp, [Bp[0] + n[0], Bp[1] + n[1]], [A[0] + n[0], A[1] + n[1]]], 'rgba(255,211,107,.45)');
        c.fillStyle = 'rgba(255,255,255,.25)'; c.beginPath(); c.moveTo(ox, oy); c.lineTo(A[0], A[1]); c.lineTo(Bp[0], Bp[1]); c.closePath(); c.fill(); c.strokeStyle = '#fff'; c.lineWidth = 2.5; c.stroke();
        txt('a² = ' + p.a * p.a, ox + p.a * sc / 2, oy + p.a * sc / 2 + 5, '#fff', 14); txt('b² = ' + p.b * p.b, ox - p.b * sc / 2, oy - p.b * sc / 2 + 5, '#fff', 14); txt('c² = ' + (p.a * p.a + p.b * p.b), (A[0] + Bp[0] + n[0]) / 2, (A[1] + Bp[1] + n[1]) / 2, '#fff', 14); } },
    transform: { res: p => { const q = TF(p); return { "Titik A'": '(' + f2(q[0][0]) + ', ' + f2(q[0][1]) + ')', "Titik B'": '(' + f2(q[1][0]) + ', ' + f2(q[1][1]) + ')', "Titik C'": '(' + f2(q[2][0]) + ', ' + f2(q[2][1]) + ')' }; },
      draw(p) { const m = box(-10, 10, -10, 10, 'x', 'y'), poly = (pts, fill, st) => { c.fillStyle = fill; c.strokeStyle = st; c.lineWidth = 2.5; c.beginPath(); pts.forEach(([x, y], i) => i ? c.lineTo(m.X(x), m.Y(y)) : c.moveTo(m.X(x), m.Y(y))); c.closePath(); c.fill(); c.stroke(); };
        poly([[1, 1], [3, 1], [1, 3]], 'rgba(89,165,255,.4)', '#59a5ff'); poly(TF(p), 'rgba(255,211,107,.4)', '#ffd36b'); txt('biru = bangun awal · kuning = bayangan', W / 2, 26, '#cfd0ff', 14); } },
    normal: { res: p => ({ ['Peluang μ ± ' + p.k + 'σ (%)']: erf(p.k / Math.SQRT2) * 100, 'Rentang nilai': f2(p.mu - p.k * p.sigma) + ' – ' + f2(p.mu + p.k * p.sigma) }),
      draw(p) { const pdf = x => Math.exp(-(((x - p.mu) / p.sigma) ** 2) / 2) / (p.sigma * Math.sqrt(2 * PI)), x0 = p.mu - 4 * p.sigma, x1 = p.mu + 4 * p.sigma, m = box(x0, x1, 0, .42 / p.sigma, 'Nilai', 'Kepadatan');
        c.fillStyle = 'rgba(139,125,240,.5)'; c.beginPath(); c.moveTo(m.X(p.mu - p.k * p.sigma), m.Y(0)); for (let i = 0; i <= 100; i++) { const x = p.mu - p.k * p.sigma + 2 * p.k * p.sigma * i / 100; c.lineTo(m.X(x), m.Y(pdf(x))); } c.lineTo(m.X(p.mu + p.k * p.sigma), m.Y(0)); c.fill(); plot(pdf, x0, x1, m, '#ffd36b'); txt('Area ungu = ' + f2(erf(p.k / Math.SQRT2) * 100) + '% data', W / 2, 26, '#fff', 15); } },
    prob: { res: p => { let s = 0; const pts = [], st = Math.max(1, Math.floor(p.n / 200)); for (let i = 1; i <= p.n; i++) { if (Math.random() < p.p) s++; if (i % st === 0 || i === p.n) pts.push([i, s / i]); } probSim = { n: p.n, pts }; const fr = s / p.n; return { 'Frekuensi relatif sukses': fr, 'Peluang teoretis': p.p, 'Selisih': Math.abs(fr - p.p) }; },
      draw(p, k, t, run) { const n = probSim ? probSim.n : p.n, m = box(0, n, 0, 1, 'Jumlah percobaan', 'Frekuensi relatif'); c.setLineDash([6, 6]); line(L, m.Y(p.p), R, m.Y(p.p), '#ffd36b', 1.5); c.setLineDash([]); txt('peluang teoretis = ' + p.p, R - 6, m.Y(p.p) - 8, '#ffd36b', 12, 'right');
        if (probSim) { const q = run && k < 1 ? Math.max(2, Math.ceil(probSim.pts.length * k)) : probSim.pts.length; c.strokeStyle = '#a99cff'; c.lineWidth = 2.5; c.beginPath(); for (let i = 0; i < q; i++) { const [x, y] = probSim.pts[i]; i ? c.lineTo(m.X(x), m.Y(y)) : c.moveTo(m.X(x), m.Y(y)); } c.stroke(); } else txt('Tekan "Jalankan Simulasi" untuk melempar', W / 2, H / 2, '#cfd0ff', 15); } }
  };
};
