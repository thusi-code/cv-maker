<?php
include 'db.php';

// Personal Details (assuming only one row)
$personal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM personal_details LIMIT 1"));

// Programming Skills
$progSkills = [];
$result = mysqli_query($conn, "SELECT skill, proficiency FROM programming_skills");
while ($row = mysqli_fetch_assoc($result)) {
    $progSkills[] = $row;
}

// Soft Skills
$softSkills = [];
$result = mysqli_query($conn, "SELECT skill, proficiency FROM soft_skills");
while ($row = mysqli_fetch_assoc($result)) {
    $softSkills[] = $row;
}

// Language Skills
$langSkills = [];
$result = mysqli_query($conn, "SELECT language, proficiency FROM language_skills");
while ($row = mysqli_fetch_assoc($result)) {
    $langSkills[] = $row;
}

// Education
$education = [];
$result = mysqli_query($conn, "SELECT degree, institute, period FROM education ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $education[] = $row;
}

// Professional Qualification
$profQual = [];
$result = mysqli_query($conn, "SELECT qualification, institute, year FROM professional_qualification ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $profQual[] = $row;
}

// Experience
$experience = [];
$result = mysqli_query($conn, "SELECT title, company, period, description FROM experience ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $experience[] = $row;
}

// References
$references = [];
$result = mysqli_query($conn, "SELECT name, position, organization, email, phone FROM `references`");
while ($row = mysqli_fetch_assoc($result)) {
    $references[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Michael Brown - Software Engineer Resume</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="cv.css">
</head>
<body>
  <div class="resume-container">
    <aside class="sidebar">
      <div class="profile-image">
        <!-- Replace 'profile.jpg' with your own photo file name -->
        <img src="https://placehold.co/150x150" alt="Profile Photo">
      </div>
      <section class="personal-details-section">
        <h2 class="section-title">PERSONAL DETAILS</h2>
        <ul class="personal-details-list">
          <li><span class="icon">&#128100;</span> <?= htmlspecialchars($personal['name']) ?></li>
          <li><span class="icon">&#128231;</span> <?= htmlspecialchars($personal['email']) ?></li>
          <li><span class="icon">&#9742;</span> <?= htmlspecialchars($personal['phone']) ?></li>
          <li><span class="icon">&#8962;</span> <?= htmlspecialchars($personal['location']) ?></li>
          <!-- Add more fields if present in DB -->
        </ul>
      </section>
      <section class="skills-section">
        <h2 class="section-title">PROGRAMMING SKILLS</h2>
        <?php foreach ($progSkills as $skill): ?>
        <div class="skill">
          <span><?= htmlspecialchars($skill['skill']) ?></span>
          <div class="progress-bar"><div class="progress" style="width:<?= (int)$skill['proficiency'] ?>%"></div></div>
        </div>
        <?php endforeach; ?>
      </section>
      <section class="skills-section">
        <h2 class="section-title">SOFT SKILLS</h2>
        <?php foreach ($softSkills as $skill): ?>
        <div class="skill">
          <span><?= htmlspecialchars($skill['skill']) ?></span>
          <div class="progress-bar"><div class="progress" style="width:<?= (int)$skill['proficiency'] ?>%"></div></div>
        </div>
        <?php endforeach; ?>
      </section>
      <section class="skills-section">
        <h2 class="section-title">LANGUAGE SKILLS</h2>
        <?php foreach ($langSkills as $lang): ?>
        <div class="skill">
          <span><?= htmlspecialchars($lang['language']) ?></span>
          <div class="progress-bar"><div class="progress" style="width:<?= (int)$lang['proficiency'] ?>%"></div></div>
        </div>
        <?php endforeach; ?>
      </section>
    </aside>
    <main class="main-content">
      <header>
        <h1>
          <span class="black-text"><?= htmlspecialchars(explode(' ', $personal['name'])[0]) ?></span>
          <span class="brown-text"><?= htmlspecialchars(explode(' ', $personal['name'])[1] ?? '') ?></span>
        </h1>
        <h2 class="job-title"><?= htmlspecialchars($personal['position'] ?? 'SOFTWARE ENGINEER') ?></h2>
      </header>
      <section class="profile-summary">
        <p>
          Innovative and results-driven Software Engineer with a strong background in designing, developing, and deploying scalable web and enterprise applications. Proficient in a wide array of programming languages and frameworks. Adept at collaborating with cross-functional teams, troubleshooting complex issues, and delivering robust solutions that drive business success. Passionate about continuous learning and leveraging new technologies to solve real-world problems.
        </p>
      </section>
      <section class="education-section-main">
        <h2 class="section-title work-title">EDUCATION QUALIFICATION</h2>
        <?php foreach ($education as $edu): ?>
        <div class="education-entry-main">
          <p class="degree-title"><?= htmlspecialchars($edu['degree']) ?></p>
          <p class="education-years"><?= htmlspecialchars($edu['period']) ?></p>
          <p class="university-name"><?= htmlspecialchars($edu['institute']) ?></p>
        </div>
        <?php endforeach; ?>
      </section>
      <section class="professional-qualification-section">
        <h2 class="section-title work-title">PROFESSIONAL QUALIFICATION</h2>
        <ul class="professional-qualification-list">
          <?php foreach ($profQual as $qual): ?>
          <li><?= htmlspecialchars($qual['qualification']) ?>, <?= htmlspecialchars($qual['institute']) ?> (<?= htmlspecialchars($qual['year']) ?>)</li>
          <?php endforeach; ?>
        </ul>
      </section>
      <section class="work-experience-section">
        <h2 class="section-title work-title">EXPERIENCE</h2>
        <?php foreach ($experience as $exp): ?>
        <div class="work-entry">
          <h3 class="work-role"><?= htmlspecialchars($exp['title']) ?></h3>
          <p class="work-dates"><?= htmlspecialchars($exp['period']) ?></p>
          <p class="work-company"><?= htmlspecialchars($exp['company']) ?></p>
          <?php if (!empty($exp['description'])): ?>
          <ul>
            <?php foreach (explode("\n", $exp['description']) as $desc): ?>
              <?php if (trim($desc)): ?>
                <li><?= htmlspecialchars($desc) ?></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </section>
      <section class="references-section">
        <h2 class="section-title work-title">REFERENCES</h2>
        <?php foreach ($references as $ref): ?>
        <div class="reference-entry">
          <strong><?= htmlspecialchars($ref['name']) ?></strong><br>
          <?= htmlspecialchars($ref['position']) ?>, <?= htmlspecialchars($ref['organization']) ?><br>
          <?php if ($ref['phone']): ?>Phone: <?= htmlspecialchars($ref['phone']) ?><br><?php endif; ?>
          <?php if ($ref['email']): ?>Email: <?= htmlspecialchars($ref['email']) ?><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </section>
    </main>
  </div>
</body>
</html>