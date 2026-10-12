<?php
declare(strict_types=1);

function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.html');
    exit;
}

$name = trim((string)($_POST['full_name'] ?? ''));
$admission = trim((string)($_POST['admission_number'] ?? ''));
$year = trim((string)($_POST['year_of_study'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$category = (string)($_POST['category'] ?? '');
$days = filter_var($_POST['days'] ?? null, FILTER_VALIDATE_INT);
$workshop = (string)($_POST['workshop'] ?? '');
$secondWorkshop = isset($_POST['second_workshop']) && $_POST['second_workshop'] === '1';
$meals = isset($_POST['meals']) && $_POST['meals'] === 'yes';

$errors = [];
if ($name === '') $errors[] = 'Full name is required.';
if ($admission === '') $errors[] = 'Admission number is required.';
if (!in_array($year, ['Year 1','Year 2','Year 3','Year 4','Year 5','Postgraduate'], true)) $errors[] = 'Select a valid year of study.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
if (!preg_match('/^\d{10}$/', $phone)) $errors[] = 'Phone number must contain exactly 10 digits.';
if (!in_array($category, ['conference','workshop'], true)) $errors[] = 'Select a valid participation category.';
if ($days === false || $days < 1 || $days > 3) $errors[] = 'Conference days must be between 1 and 3.';
$workshops = ['web' => 'Web Development', 'data' => 'Data & AI', 'cyber' => 'Cybersecurity'];
if ($category === 'workshop' && !isset($workshops[$workshop])) $errors[] = 'Select a valid workshop.';
if ($category !== 'workshop' && $secondWorkshop) $errors[] = 'A second workshop requires workshop participation.';

if ($errors) {
    http_response_code(422);
}

$total = 1000 + (($days !== false && $days >= 1 && $days <= 3) ? $days * 500 : 0);
if ($category === 'workshop') $total += 750;
if ($category === 'workshop' && $secondWorkshop) $total += 1000;
if ($meals && $days !== false && $days >= 1 && $days <= 3) $total += $days * 300;
$workshopDisplay = $category === 'workshop' ? ($workshops[$workshop] ?? 'Invalid workshop') : 'Not selected';
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $errors ? 'Check your details' : 'Registration confirmed' ?> | STIC 2026</title><link rel="stylesheet" href="css/style.css"></head>
<body class="confirmation-page">
<header class="site-header"><a class="brand" href="index.html"><span class="brand-mark">S</span><span>STIC <small>2026</small></span></a><nav aria-label="Main navigation"><a href="index.html">Home</a><a class="active" href="register.html">Registration</a><a href="index.html#about">About</a></nav><a class="button button-small" href="register.html">← Back to form</a></header>
<main class="confirm-wrap">
<?php if ($errors): ?>
  <section class="confirm-card error-card"><p class="eyebrow">A QUICK CHECK</p><h1>Please review your <span>details.</span></h1><p>Some information needs attention before we can confirm your registration.</p><ul class="server-errors"><?php foreach ($errors as $error): ?><li><?= escape($error) ?></li><?php endforeach; ?></ul><a class="button" href="register.html">Return to registration <span aria-hidden="true">→</span></a></section>
<?php else: ?>
  <div class="confirm-top"><div class="success-icon">✓</div><p class="eyebrow">YOU'RE ON THE LIST</p><h1>Registration <span>successful.</span></h1><p>Thanks for joining us, <?= escape($name) ?>. We look forward to seeing you in November.</p></div>
  <section class="confirm-card"><div class="confirm-card-head"><div><span class="fee-label">STUDENT TECHNOLOGY &amp; INNOVATION CONFERENCE</span><h2>Your registration</h2></div><span class="status-pill">CONFIRMED</span></div><div class="confirm-grid"><div><small>Participant</small><strong><?= escape($name) ?></strong></div><div><small>Admission number</small><strong><?= escape($admission) ?></strong></div><div><small>Email address</small><strong><?= escape($email) ?></strong></div><div><small>Phone number</small><strong><?= escape($phone) ?></strong></div><div><small>Year of study</small><strong><?= escape($year) ?></strong></div><div><small>Participation</small><strong><?= $category === 'workshop' ? 'Workshop participant' : 'Conference only' ?></strong></div><div><small>Conference days</small><strong><?= (int)$days ?> <?= (int)$days === 1 ? 'day' : 'days' ?></strong></div><div><small>Workshop</small><strong><?= escape($workshopDisplay) ?></strong></div><div><small>Second workshop</small><strong><?= $secondWorkshop ? 'Yes · KSh 1,000' : 'No' ?></strong></div><div><small>Lunch</small><strong><?= $meals ? 'Yes · KSh 300 per day' : 'No' ?></strong></div></div><div class="confirm-total"><span>TOTAL AMOUNT PAYABLE</span><strong>KSh <?= number_format($total) ?></strong></div></section>
  <p class="confirm-thanks">Keep this confirmation for your records. See you at the University Innovation Hub, Nairobi.</p><a class="button" href="index.html">Back to conference home <span aria-hidden="true">→</span></a>
<?php endif; ?>
</main>
<footer><a class="brand" href="index.html"><span class="brand-mark">S</span><span>STIC <small>2026</small></span></a><span>Student Technology &amp; Innovation Conference</span><span>Nairobi, Kenya · 18–20 November 2026</span></footer>
</body></html>
