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
    try { localStorage.setItem('theme2', t); } catch (e) {}
  };
  let saved = 'dark';
  try { saved = localStorage.getItem('theme2') || 'dark'; } catch (e) {}
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
    'Git & GitHub': 'Version control and publishing code.',
    'PC Building': 'Choosing parts and assembling desktop PCs.', 'Troubleshooting': 'Finding and fixing hardware and software problems.',
    'VS Code': 'My code editor for web projects.', 'XAMPP': 'Local server for testing PHP and MySQL.'
  };
  const note = $('#skillNote'), noteDefault = note.textContent;
  $$('.chip').forEach(c => {
    const show = () => note.textContent = c.textContent + ': ' + (info[c.textContent] || '');
    c.addEventListener('mouseover', show);
    c.addEventListener('focus', show);
    c.addEventListener('mouseout', () => note.textContent = noteDefault);
    c.addEventListener('blur', () => note.textContent = noteDefault);
  });

  // 3b. scroll event: highlight the nav link of the section in view
  const navLinks = $$('.pill-nav a');
  const spy = () => {
    let cur = navLinks[0];
    navLinks.forEach(a => { const sec = $(a.getAttribute('href')); if (sec && sec.getBoundingClientRect().top <= 140) cur = a; });
    navLinks.forEach(a => a.classList.toggle('active', a === cur));
  };
  window.addEventListener('scroll', spy);
  spy();

  // 3c. numbers count up when they scroll into view
  const io = new IntersectionObserver(entries => entries.forEach(en => {
    if (!en.isIntersecting) return;
    const el = en.target, end = +el.dataset.count;
    let n = 0;
    const t = setInterval(() => { n++; el.textContent = String(n).padStart(2, '0') + '+'; if (n >= end) clearInterval(t); }, 120);
    io.unobserve(el);
  }), { threshold: 0.6 });
  $$('.stat b').forEach(n => io.observe(n));

  // 5. click + input events: filter buttons and live search
  const cards = $$('#grid .card');
  let activeFilter = 'all';
  const applyFilter = () => {
    const q = $('#search').value.trim().toLowerCase();
    let shown = 0;
    cards.forEach(c => {
      const ok = (activeFilter === 'all' || c.dataset.cat.split(' ').includes(activeFilter)) &&
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

  // 6. keydown event: "/" focuses search, Esc closes modals
  document.addEventListener('keydown', e => {
    const typing = /INPUT|TEXTAREA/.test(document.activeElement.tagName);
    if (e.key === '/' && !typing) { e.preventDefault(); $('#search').focus(); }
    if (e.key === 'Escape') { closeModal(); closeLogin(); }
  });

  // 7. click event (event delegation): project details modal
  const modal = $('#modal');
  const openModal = c => {
    $('#mTitle').textContent = c.dataset.title;
    $('#mDesc').textContent = c.dataset.desc;
    $('#mTech').textContent = c.dataset.tech;
    $('#mLink').href = c.dataset.link;
    const shot = $('#mShot');
    shot.hidden = true;
    shot.onload = () => shot.hidden = false;
    shot.onerror = () => shot.hidden = true;
    shot.alt = 'Screenshot of ' + c.dataset.title;
    if (c.dataset.shot) shot.src = c.dataset.shot; else shot.removeAttribute('src');
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

  // 9. click / submit events: Admin Login & Logout
  const loginModal = $('#loginModal');
  const loginBtn = $('#loginBtn');
  const logoutBtn = $('#logoutBtn');
  const closeLoginBtn = $('#closeLogin');
  const loginForm = $('#loginForm');
  const togglePass = $('#togglePass');
  const loginPass = $('#loginPass');

  function closeLogin() {
    if (loginModal) loginModal.hidden = true;
  }

  if (loginBtn) {
    loginBtn.addEventListener('click', () => {
      loginModal.hidden = false;
      $('#loginUser').focus();
    });
  }

  if (closeLoginBtn) closeLoginBtn.addEventListener('click', closeLogin);
  if (loginModal) loginModal.addEventListener('click', e => { if (e.target === loginModal) closeLogin(); });

  // Toggle password visibility (click event)
  if (togglePass && loginPass) {
    togglePass.addEventListener('click', () => {
      const isPass = loginPass.type === 'password';
      loginPass.type = isPass ? 'text' : 'password';
      togglePass.textContent = isPass ? 'Hide' : 'Show';
    });
  }

  // Submit login form
  if (loginForm) {
    loginForm.addEventListener('submit', async e => {
      e.preventDefault();
      const status = $('#loginStatus');
      const user = $('#loginUser').value.trim();
      const pass = $('#loginPass').value;

      if (!user || !pass) {
        status.textContent = 'Please enter both username and password.';
        return;
      }

      status.textContent = 'Signing in...';
      try {
        const res = await fetch('auth.php', { method: 'POST', body: new FormData(loginForm) });
        const data = await res.json();
        status.textContent = data.message;
        if (data.ok) {
          window.location.reload();
        }
      } catch (err) {
        status.textContent = 'Could not reach the server. Run through PHP.';
      }
    });
  }

  // Logout click event
  if (logoutBtn) {
    logoutBtn.addEventListener('click', async () => {
      const fd = new FormData();
      fd.append('action', 'logout');
      await fetch('auth.php', { method: 'POST', body: fd });
      window.location.reload();
    });
  }
});
