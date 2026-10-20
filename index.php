<?php
// Edit your info and projects here. PHP renders them into the page.
$owner = [
  'name'  => 'Joros',
  'role'  => 'IT Student',
  'bio'   => 'I build and fix things: web apps, PCs, and tools that solve real problems. This portfolio is my Event-Driven Programming midterm project.',
  'email' => 'your-email@example.com',
  'github'=> 'https://github.com/your-username',
];
$skills = ['HTML', 'CSS', 'JavaScript', 'PHP', 'MySQL', 'PC Hardware', 'Git & GitHub'];
$projects = [
  ['title' => 'Still Becoming', 'cat' => 'web',   'desc' => 'A personal journal website built with HTML, CSS and vanilla JavaScript. Entries are saved in localStorage.', 'tech' => 'HTML, CSS, JS', 'link' => '#'],
  ['title' => 'Community Reporting Platform', 'cat' => 'web', 'desc' => 'Capstone concept: residents report local concerns and the barangay tracks them.', 'tech' => 'PHP, MySQL, JS', 'link' => '#'],
  ['title' => 'PC Build & Troubleshooting', 'cat' => 'hardware', 'desc' => 'Hands-on builds, diagnostics and repairs for desktop PCs.', 'tech' => 'Hardware', 'link' => '#'],
  ['title' => 'Event-Driven Midterm', 'cat' => 'web', 'desc' => 'This portfolio: click, input, keyboard, scroll and submit events handled in JavaScript, with a PHP backend.', 'tech' => 'HTML, CSS, JS, PHP', 'link' => '#'],
];
function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($owner['name']) ?> | Portfolio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Figtree:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div id="progress" aria-hidden="true"></div>

  <header class="top">
    <a class="brand" href="#home"><?= e($owner['name']) ?></a>
    <nav>
      <a href="#about">About</a>
      <a href="#projects">Projects</a>
      <a href="#contact">Contact</a>
      <button id="themeBtn" type="button" aria-label="Toggle dark mode">Dark mode</button>
    </nav>
  </header>

  <main>
    <section id="home" class="hero">
      <h1>Hi, I'm <?= e($owner['name']) ?>.<br><?= e($owner['role']) ?> who builds for the web.</h1>
      <p id="greeting"></p>
      <a class="btn" href="#projects">See my projects</a>
    </section>

    <section id="about">
      <h2>About me</h2>
      <p class="lead"><?= e($owner['bio']) ?></p>
      <ul class="chips">
        <?php foreach ($skills as $s): ?>
          <li class="chip" tabindex="0"><?= e($s) ?></li>
        <?php endforeach; ?>
      </ul>
      <p id="skillNote" class="note">Hover or focus a skill to see it here.</p>
    </section>

    <section id="projects">
      <h2>Projects</h2>
      <div class="tools">
        <div class="filters" role="group" aria-label="Filter projects">
          <button class="filter active" data-filter="all" type="button">All</button>
          <button class="filter" data-filter="web" type="button">Web</button>
          <button class="filter" data-filter="hardware" type="button">Hardware</button>
        </div>
        <input id="search" type="search" placeholder="Search projects (press / to focus)" aria-label="Search projects">
      </div>
      <div class="grid" id="grid">
        <?php foreach ($projects as $p): ?>
          <article class="card" data-cat="<?= e($p['cat']) ?>"
                   data-title="<?= e($p['title']) ?>" data-desc="<?= e($p['desc']) ?>"
                   data-tech="<?= e($p['tech']) ?>" data-link="<?= e($p['link']) ?>" tabindex="0">
            <h3><?= e($p['title']) ?></h3>
            <p><?= e($p['desc']) ?></p>
            <span class="tag"><?= e($p['tech']) ?></span>
          </article>
        <?php endforeach; ?>
      </div>
      <p id="empty" class="note" hidden>No project matches your search. Clear the box or pick "All".</p>
    </section>

    <section id="contact">
      <h2>Contact me</h2>
      <form id="contactForm" action="contact.php" method="post" novalidate>
        <label>Name
          <input type="text" name="name" id="name" required>
          <small class="err" data-for="name"></small>
        </label>
        <label>Email
          <input type="email" name="email" id="email" required>
          <small class="err" data-for="email"></small>
        </label>
        <label>Message
          <textarea name="message" id="message" rows="5" maxlength="500" required></textarea>
          <small id="count">0 / 500</small>
          <small class="err" data-for="message"></small>
        </label>
        <button class="btn" type="submit">Send message</button>
        <p id="status" role="status"></p>
      </form>
    </section>
  </main>

  <footer>
    <p>&copy; <?= date('Y') ?> <?= e($owner['name']) ?> &middot; <a href="<?= e($owner['github']) ?>" target="_blank" rel="noopener">GitHub</a></p>
  </footer>

  <div id="modal" class="modal" hidden>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="mTitle">
      <button id="closeModal" type="button" aria-label="Close">Close</button>
      <h3 id="mTitle"></h3>
      <p id="mDesc"></p>
      <p><strong>Tech:</strong> <span id="mTech"></span></p>
      <a id="mLink" class="btn" href="#" target="_blank" rel="noopener">View project</a>
    </div>
  </div>

  <script src="js/script.js"></script>
</body>
</html>
