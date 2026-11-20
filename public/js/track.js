// Menghitung waktu AKTIF: tab terlihat + ada gerakan/ketikan dalam 60 detik terakhir.
(() => {
  const cfg = window.TRACK; if (!cfg) return;
  let acc = 0, lastAct = Date.now();
  ['mousemove', 'keydown', 'scroll', 'click', 'touchstart', 'input'].forEach(e => addEventListener(e, () => { lastAct = Date.now(); }, { passive: true }));
  const send = beacon => {
    const s = Math.min(60, Math.floor(acc)); if (s < 1) return; acc -= s;
    const fd = new FormData(); fd.append('_token', cfg.token); fd.append('material_id', cfg.material); fd.append('seconds', s);
    if (beacon && navigator.sendBeacon) navigator.sendBeacon(cfg.url, fd);
    else fetch(cfg.url, { method: 'POST', body: fd, keepalive: true, credentials: 'same-origin' }).catch(() => {});
  };
  setInterval(() => { if (document.visibilityState === 'visible' && Date.now() - lastAct < 60000) acc += 1; }, 1000);
  setInterval(() => send(false), 15000);
  document.addEventListener('visibilitychange', () => { if (document.visibilityState === 'hidden') send(true); });
  addEventListener('pagehide', () => send(true));
})();
