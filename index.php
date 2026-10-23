<?php
session_start();
$isLoggedIn = !empty($_SESSION['admin']);

// ====== EDIT YOUR INFO HERE ======
$owner = [
  'name'    => 'Joros',
  'tagline' => 'Aspiring IT Professional | Future Developer',
  'intro'   => 'I am an IT student who enjoys building websites and fixing computers. This portfolio is my Event-Driven Programming midterm project.',
  'email'   => 'your-email@example.com',
  'github'  => 'https://github.com/joro-labs',
  'linkedin'=> 'https://www.linkedin.com/in/your-profile',
];
$about = [
  'Background'       => 'I am an Information Technology student in the Philippines. I learned web development and PC hardware through school work and hands-on practice.',
  'Interests'        => 'Web development, PC building and troubleshooting, and creating tools that help people.',
  'Goals'            => 'To become a full-stack developer and build systems that solve real problems for my community.',
  'Learning journey' => 'Right now I am learning event-driven programming with JavaScript and PHP, plus Git and GitHub for version control.',
];
$skills = [
  'Frontend'           => ['HTML', 'CSS', 'JavaScript', 'Landing Page Development'],
  'Backend'            => ['PHP', 'MySQL', 'Python', 'JavaScript / Node.js'],
  'Networking'         => ['IP Addressing', 'DNS & DHCP', 'TCP/IP', 'Network Troubleshooting'],
  'Systems & Security' => ['Windows Administration', 'Linux (Ubuntu)', 'PowerShell'],
];
// 'img' = file name (no extension) inside the img/ folder. .jpg .jpeg .png .webp all work.
// Filter buttons shown in Projects (Frontend + Backend merged into "Web")
$filters = ['Web', 'Networking', 'Systems & Security'];
$projects = [
  ['title' => 'Still Becoming', 'cat' => 'frontend', 'img' => 'still-becoming', 'desc' => 'A personal journal website where entries are saved in the browser using localStorage.', 'tech' => 'HTML, CSS, JavaScript', 'link' => '#'],
  ['title' => 'Community Reporting Platform', 'cat' => 'frontend backend', 'img' => 'community-reporting', 'desc' => 'Capstone concept: residents report local concerns and the barangay tracks them.', 'tech' => 'PHP, MySQL, JavaScript', 'link' => '#'],
  ['title' => 'PC Build & Troubleshooting', 'cat' => 'systems-security', 'img' => 'pc-build', 'desc' => 'Hands-on PC builds, diagnostics and repairs.', 'tech' => 'Hardware', 'link' => '#'],
  ['title' => 'Event-Driven Midterm', 'cat' => 'frontend backend', 'img' => 'midterm-portfolio', 'desc' => 'This portfolio: click, input, keyboard, scroll and submit events in JavaScript with a PHP backend.', 'tech' => 'HTML, CSS, JavaScript, PHP', 'link' => 'https://github.com/joro-labs/portfolio'],
];

// Read saved contact messages if admin is logged in
$messages = [];
$msgFile = __DIR__ . '/data/messages.txt';
if ($isLoggedIn && file_exists($msgFile)) {
  $lines = file($msgFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach (array_reverse($lines) as $line) {
    $decoded = json_decode($line, true);
    if ($decoded) $messages[] = $decoded;
  }
}

function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function slug($s){ return trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $s)), '-'); }
function find_img($base){
  foreach (['jpg','jpeg','png','webp'] as $x) {
    if (file_exists(__DIR__ . "/img/$base.$x")) return "img/$base.$x";
  }
  return '';
}
$profile = find_img('profile');
$parts = array_map('trim', explode('|', $owner['tagline']));
$skillCount = 0; foreach ($skills as $g) $skillCount += count($g);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($owner['name']) ?> | Portfolio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div id="progress" aria-hidden="true"></div>

  <header class="top">
    <a class="logo" href="#home" aria-label="Home"><?= e(strtoupper(substr($owner["name"], 0, 1))) ?></a>
    <nav class="pill-nav">
      <a href="#home">Home</a>
      <a href="#about">About</a>
      <a href="#projects">Projects</a>
      <a href="#skills">Skills</a>
      <a href="#contact">Contact</a>
      <?php if ($isLoggedIn): ?>
        <a href="#inbox">Inbox (<?= count($messages) ?>)</a>
      <?php endif; ?>
    </nav>
    <div class="actions">
      <button id="themeBtn" class="btn-sm" type="button" aria-label="Toggle theme">Light mode</button>
      <?php if ($isLoggedIn): ?>
        <button id="logoutBtn" class="btn-sm" type="button">Logout (<?= e($_SESSION['admin']) ?>)</button>
      <?php else: ?>
        <button id="loginBtn" class="btn-sm" type="button">Login</button>
      <?php endif; ?>
      <a class="btn-sm" href="#contact">Let's Talk</a>
    </div>
  </header>

  <main>
    <section id="home" class="hero">
      <div class="wrap hero-in">
        <div class="hero-text">
          <p class="small">I am <?= e($owner['name']) ?></p>
          <h1><?= e($parts[0]) ?><?php if (!empty($parts[1])): ?><br><?= e($parts[1]) ?><?php endif; ?></h1>
          <p class="intro"><?= e($owner['intro']) ?></p>
          <p id="greeting"></p>
          <a class="btn" href="#contact">Contact me</a>
          <div class="socials">
            <a href="<?= e($owner['github']) ?>" target="_blank" rel="noopener">GitHub</a>
            <a href="<?= e($owner['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn</a>
          </div>
        </div>
        <div class="photo">
          <?php if ($profile): ?>
            <img src="<?= e($profile) ?>" alt="Photo of <?= e($owner['name']) ?>">
          <?php else: ?>
            <div class="photo-fallback"><?= e(strtoupper(substr($owner["name"], 0, 1))) ?></div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section id="about">
      <div class="wrap">
        <div class="sec-head"><h2>About Me</h2><p>Who I am and where I'm headed</p></div>
        <p class="about-lead"><?= e($owner['intro']) ?></p>
        <div class="about-grid">
          <?php $i = 0; foreach ($about as $title => $text): $i++; ?>
            <div class="about-item">
              <span class="num"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></span>
              <div><h3><?= e($title) ?></h3><p><?= e($text) ?></p></div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="stats">
          <div class="stat"><b data-count="<?= count($projects) ?>"><?= str_pad(count($projects), 2, "0", STR_PAD_LEFT) ?>+</b><span>Projects</span></div>
          <div class="stat"><b data-count="<?= $skillCount ?>"><?= str_pad($skillCount, 2, "0", STR_PAD_LEFT) ?>+</b><span>Skills</span></div>
          <div class="stat"><b data-count="<?= count($skills) ?>"><?= str_pad(count($skills), 2, "0", STR_PAD_LEFT) ?>+</b><span>Skill areas</span></div>
        </div>
      </div>
    </section>

    <section id="projects">
      <div class="wrap">
        <div class="sec-head"><h2>Projects</h2><p>School work and personal coding exercises</p></div>
        <div class="tools">
          <div class="filters" role="group" aria-label="Filter projects">
            <button class="filter active" data-filter="all" type="button">All</button>
            <?php foreach (array_keys($skills) as $g): ?>
              <button class="filter" data-filter="<?= e(slug($g)) ?>" type="button"><?= e($g) ?></button>
            <?php endforeach; ?>
          </div>
          <input id="search" type="search" placeholder="Search projects (press / to focus)" aria-label="Search projects">
        </div>
        <div class="grid" id="grid">
          <?php foreach ($projects as $p): $img = find_img($p['img']); ?>
            <article class="card" data-cat="<?= e($p['cat']) ?>"
                     data-title="<?= e($p['title']) ?>" data-desc="<?= e($p['desc']) ?>"
                     data-tech="<?= e($p['tech']) ?>" data-link="<?= e($p['link']) ?>" data-shot="<?= e($img) ?>" tabindex="0">
              <div class="shot-wrap">
                <?php if ($img): ?>
                  <img class="shot" src="<?= e($img) ?>" alt="Screenshot of <?= e($p['title']) ?>" loading="lazy">
                <?php else: ?>
                  <div class="shot-empty"><?= e($p['title']) ?></div>
                <?php endif; ?>
              </div>
              <h3><?= e($p['title']) ?></h3>
              <p><?= e($p['desc']) ?></p>
              <ul class="techs">
                <?php foreach (explode(',', $p['tech']) as $t): ?><li><?= e(trim($t)) ?></li><?php endforeach; ?>
              </ul>
            </article>
          <?php endforeach; ?>
        </div>
        <p id="empty" class="note" hidden>No project matches your search. Clear the box or pick "All".</p>
      </div>
    </section>

    <section id="skills">
      <div class="wrap">
        <div class="sec-head"><h2>Skills</h2><p>A showcase of technical skills, technologies, and tools developed through hands-on practice and continuous learning.</p></div>
        <div class="skill-grid">
          <?php foreach ($skills as $group => $items): ?>
            <div class="card plain">
              <h3><?= e($group) ?></h3>
              <ul class="techs">
                <?php foreach ($items as $s): ?><li class="chip" tabindex="0"><?= e($s) ?></li><?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        </div>
        <p id="skillNote" class="note center">Hover or focus a skill to see what I use it for.</p>
      </div>
    </section>

    <section id="contact">
      <div class="wrap">
        <div class="sec-head"><h2>Contact</h2><p>Let's talk about your project or just say hi</p></div>
        <div class="card plain contact-grid">
          <ul class="links">
            <li><span>Email</span><a href="mailto:<?= e($owner['email']) ?>"><?= e($owner['email']) ?></a></li>
            <li><span>GitHub</span><a href="<?= e($owner['github']) ?>" target="_blank" rel="noopener"><?= e($owner['github']) ?></a></li>
            <li><span>LinkedIn</span><a href="<?= e($owner['linkedin']) ?>" target="_blank" rel="noopener"><?= e($owner['linkedin']) ?></a></li>
          </ul>
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
        </div>
      </div>
    </section>

    <?php if ($isLoggedIn): ?>
    <section id="inbox">
      <div class="wrap">
        <div class="sec-head"><h2>Admin Inbox</h2><p>Messages sent through the contact form</p></div>
        <?php if (empty($messages)): ?>
          <p class="note center">No messages received yet.</p>
        <?php else: ?>
          <div class="grid">
            <?php foreach ($messages as $m): ?>
              <div class="card plain">
                <div class="msg-head">
                  <h3><?= e($m['name'] ?? 'Unknown') ?></h3>
                  <small class="note"><?= e( isset($m['time']) ? date('M j, Y g:i A', strtotime($m['time'])) : '' ) ?></small>
                </div>
                <p class="msg-email"><a href="mailto:<?= e($m['email'] ?? '') ?>"><?= e($m['email'] ?? '') ?></a></p>
                <p class="msg-body"><?= nl2br(e($m['message'] ?? '')) ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
    <?php endif; ?>
  </main>

  <footer>
    <p>&copy; <?= date('Y') ?> <?= e($owner['name']) ?> &middot; <a href="<?= e($owner['github']) ?>" target="_blank" rel="noopener">GitHub</a></p>
  </footer>

  <!-- Project Details Modal -->
  <div id="modal" class="modal" hidden>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="mTitle">
      <button id="closeModal" type="button" aria-label="Close">Close</button>
      <img id="mShot" class="shot" alt="" hidden>
      <h3 id="mTitle"></h3>
      <p id="mDesc"></p>
      <p><strong>Tech:</strong> <span id="mTech"></span></p>
      <a id="mLink" class="btn" href="#" target="_blank" rel="noopener">View project</a>
    </div>
  </div>

  <!-- Admin Login Modal -->
  <div id="loginModal" class="modal" hidden>
    <div class="modal-box login-box" role="dialog" aria-modal="true" aria-labelledby="lTitle">
      <button id="closeLogin" class="close-btn" type="button" aria-label="Close">Close</button>
      <h3 id="lTitle">Admin Login</h3>
      <p class="note">Sign in to view your contact messages.</p>
      <form id="loginForm" action="auth.php" method="post" novalidate>
        <label>Username
          <input type="text" name="username" id="loginUser" required autocomplete="username">
        </label>
        <label>Password
          <div class="pass-wrap">
            <input type="password" name="password" id="loginPass" required autocomplete="current-password">
            <button type="button" id="togglePass" class="btn-sm" aria-label="Toggle password visibility">Show</button>
          </div>
        </label>
        <button class="btn" type="submit">Sign In</button>
        <p id="loginStatus" role="status"></p>
      </form>
    </div>
  </div>

  <script src="js/script.js"></script>
</body>
</html>
