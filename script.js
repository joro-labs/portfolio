// Event-Driven Programming: every feature below is triggered by a user event.
document.addEventListener('DOMContentLoaded', () => {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  // 1. load event: greeting based on time of day
  const h = new Date().getHours();
  $('#greeting').textContent = (h < 12 ? 'Good morning' : h < 18 ? 'Good afternoon' : 'Good evening') + '! Thanks for visiting my portfolio.';

  // 2. click event: dark mode toggle (remembered with localStorage)
  const themeBtn = $('#themeBtn');
  const setTheme = t => {
    document.documentElement.dataset.theme = t;
    themeBtn.textContent = t === 'dark' ? 'Light mode' : 'Dark mode';
    try { localStorage.setItem('theme', t); } catch (e) {}
  };
  let saved = 'light';
  try { saved = localStorage.getItem('theme') || 'light'; } catch (e) {}
  setTheme(saved);
  themeBtn.addEventListener('click', () => setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark'));

  // 3. scroll event: reading progress bar
  window.addEventListener('scroll', () => {
    const max = document.documentElement.scrollHeight - innerHeight;
    $('#progress').style.width = (max > 0 ? (scrollY / max) * 100 : 0) + '%';
  });

  // 4. mouseover / focus events: skill descriptions
  const info = {
    'HTML': 'Page structure and semantic markup.', 'CSS': 'Layout, themes and responsive design.',
    'JavaScript': 'Events, DOM updates and fetch requests.', 'PHP': 'Server-side rendering and form handling.',
    'MySQL': 'Relational databases and queries.', 'PC Hardware': 'Assembly, diagnostics and repair.',
    'Git & GitHub': 'Version control and publishing code.'
  };
  const note = $('#skillNote'), noteDefault = note.textContent;
  $$('.chip').forEach(c => {
    const show = () => note.textContent = c.textContent + ': ' + (info[c.textContent] || '');
    c.addEventListener('mouseover', show);
    c.addEventListener('focus', show);
    c.addEventListener('mouseout', () => note.textContent = noteDefault);
    c.addEventListener('blur', () => note.textContent = noteDefault);
  });

  // 5. click + input events: filter buttons and live search
  const cards = $$('.card');
  let activeFilter = 'all';
  const applyFilter = () => {
    const q = $('#search').value.trim().toLowerCase();
    let shown = 0;
    cards.forEach(c => {
      const ok = (activeFilter === 'all' || c.dataset.cat === activeFilter) &&
                 (c.textContent + c.dataset.tech).toLowerCase().includes(q);
      c.hidden = !ok;
      if (ok) shown++;
    });
    $('#empty').hidden = shown > 0;
  };
  $$('.filter').forEach(b => b.addEventListener('click', () => {
    $$('.filter').forEach(x => x.classList.remove('active'));
    b.classList.add('active');
    activeFilter = b.dataset.filter;
    applyFilter();
  }));
  $('#search').addEventListener('input', applyFilter);

  // 6. keydown event: "/" focuses search, Esc closes modal
  document.addEventListener('keydown', e => {
    const typing = /INPUT|TEXTAREA/.test(document.activeElement.tagName);
    if (e.key === '/' && !typing) { e.preventDefault(); $('#search').focus(); }
    if (e.key === 'Escape') closeModal();
  });

  // 7. click event (event delegation): project details modal
  const modal = $('#modal');
  const openModal = c => {
    $('#mTitle').textContent = c.dataset.title;
    $('#mDesc').textContent = c.dataset.desc;
    $('#mTech').textContent = c.dataset.tech;
    $('#mLink').href = c.dataset.link;
    modal.hidden = false;
    $('#closeModal').focus();
  };
  function closeModal() { modal.hidden = true; }
  $('#grid').addEventListener('click', e => { const c = e.target.closest('.card'); if (c) openModal(c); });
  $('#grid').addEventListener('keydown', e => {
    const c = e.target.closest('.card');
    if (c && e.key === 'Enter') openModal(c);
  });
  $('#closeModal').addEventListener('click', closeModal);
  modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

  // 8. input / blur / submit events: form validation + PHP request
  const form = $('#contactForm');
  const rules = {
    name:    v => v.trim().length >= 2 || 'Enter your name (at least 2 characters).',
    email:   v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) || 'Enter a valid email like name@example.com.',
    message: v => v.trim().length >= 10 || 'Write at least 10 characters.'
  };
  const check = el => {
    const r = rules[el.name](el.value);
    $(`.err[data-for="${el.name}"]`).textContent = r === true ? '' : r;
    el.classList.toggle('invalid', r !== true);
    el.classList.toggle('valid', r === true);
    return r === true;
  };
  $$('#contactForm input, #contactForm textarea').forEach(el => {
    el.addEventListener('blur', () => check(el));
    el.addEventListener('input', () => { if (el.classList.contains('invalid')) check(el); });
  });
  $('#message').addEventListener('input', e => $('#count').textContent = e.target.value.length + ' / 500');

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const fields = $$('#contactForm input, #contactForm textarea');
    if (!fields.map(check).every(Boolean)) { $('#status').textContent = 'Fix the highlighted fields and send again.'; return; }
    const status = $('#status');
    status.textContent = 'Sending...';
    try {
      const res = await fetch('contact.php', { method: 'POST', body: new FormData(form) });
      const data = await res.json();
      status.textContent = data.message;
      if (data.ok) { form.reset(); $('#count').textContent = '0 / 500'; fields.forEach(f => f.classList.remove('valid')); }
    } catch (err) {
      status.textContent = 'Could not reach the server. Run the site through PHP (see README).';
    }
  });
});
