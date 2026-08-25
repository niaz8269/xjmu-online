<?php
require_once __DIR__ . '/includes/auth.php';

$user_id = (int) ($_GET['id'] ?? $_GET['user_id'] ?? 0);
if (!$user_id) {
    die("Invalid user ID.");
}

// Handle POST (status update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Invalid request token.");
  }

  $new_status = $_POST['status'] ?? '';
  $allowedStatuses = ['Pending', 'Approved', 'Rejected'];
  if (!in_array($new_status, $allowedStatuses, true)) {
    $new_status = 'Pending';
  }

  $admin_remark = trim($_POST['admin_remark'] ?? '');

    $stmt = $pdo->prepare("UPDATE applications SET status = ?, admin_remark = ? WHERE user_id = ?");
    $stmt->execute([$new_status, $admin_remark, $user_id]);

    $_SESSION['flash'] = "Application has been updated successfully.";
    header("Location: applications");
    exit;
}

// Fetch application
$stmt = $pdo->prepare("SELECT users.full_name, users.email, applications.* FROM users JOIN applications ON users.id = applications.user_id WHERE users.id = ?");
$stmt->execute([$user_id]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$app) {
    die("Application not found.");
}

// Fetch personal info
$pid = $pdo->prepare("SELECT * FROM personal_info WHERE user_id = ?");
$pid->execute([$user_id]);
$personal = $pid->fetch(PDO::FETCH_ASSOC);

// Fetch education
$edu = $pdo->prepare("SELECT * FROM education_records WHERE user_id = ?");
$edu->execute([$user_id]);
$edu_records = $edu->fetchAll(PDO::FETCH_ASSOC);

// Fetch documents
$docs = $pdo->prepare("SELECT * FROM documents WHERE user_id = ?");
$docs->execute([$user_id]);
$documents = $docs->fetchAll(PDO::FETCH_ASSOC);

$admin_page_title = 'Review Application';
$admin_active     = 'applications';
require __DIR__ . '/includes/layout_top.php';
?>

<div class="card"><div class="card-body">

  <div class="card mb-4">
    <div class="card-header fw-bold">Applicant: <?= htmlspecialchars((string)$app['full_name']) ?> (<?= htmlspecialchars((string)$app['email']) ?>)</div>
    <div class="card-body">
      <p><strong>Submitted At:</strong> <?= htmlspecialchars((string)($app['submitted_at'] ?? 'â€”')) ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars((string)($app['status'] ?? 'Not set')) ?></p>
      <?php if (!empty($app['degree_applying_for']) || !empty($app['major_applying_for'])): ?>
        <p><strong>Degree Applying for:</strong> <?= htmlspecialchars($app['degree_applying_for'] ?? 'â€”') ?></p>
        <p><strong>Major Applying for:</strong> <?= htmlspecialchars($app['major_applying_for'] ?? 'â€”') ?></p>
      <?php endif; ?>
      <?php if ($app['status'] === 'Rejected' && !empty($app['admin_remark'])): ?>
        <p><strong>Remark:</strong> <span class="text-danger"><?= htmlspecialchars($app['admin_remark']) ?></span></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Personal Information</div>
    <div class="card-body">
      <?php
        if ($personal) {
            foreach ($personal as $key => $value) {
                if (!in_array($key, ['id', 'user_id', 'created_at'])) {
                    echo "<strong>" . ucfirst(str_replace('_', ' ', $key)) . ":</strong> " . htmlspecialchars($value) . "<br>";
                }
            }
        } else {
            echo "<em>No personal info provided.</em>";
        }
      ?>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Education Records</div>
    <div class="card-body">
      <?php
        if ($edu_records) {
            echo "<ul>";
            foreach ($edu_records as $record) {
            echo "<li><strong>" . htmlspecialchars((string)$record['entry_type']) . "</strong>: "
              . htmlspecialchars((string)$record['education_level']) . " in "
              . htmlspecialchars((string)$record['field_of_study']) . " from "
              . htmlspecialchars((string)$record['institute_name']) . " ("
              . htmlspecialchars((string)$record['from_date']) . " to "
              . htmlspecialchars((string)$record['to_date']) . ")</li>";
            }
            echo "</ul>";
        } else {
            echo "<em>No education records found.</em>";
        }
      ?>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Uploaded Documents</div>
    <div class="card-body">
      <?php
        if ($documents) {
            echo "<ul>";
            foreach ($documents as $doc) {
                echo "<li><a href='../" . htmlspecialchars($doc['file_path']) . "' target='_blank' rel='noopener noreferrer'>" . htmlspecialchars($doc['file_name']) . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<em>No documents uploaded.</em>";
        }
      ?>
    </div>
  </div>

  <!-- Approve / Reject Form -->
  <form method="POST" class="border rounded p-4 bg-white shadow-sm">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
    <h5 class="mb-3">Update Application Status</h5>
    <div class="mb-3">
      <label for="status" class="form-label">Status:</label>
      <select class="form-select" name="status" id="status" required>
        <option value="">-- Select --</option>
        <option value="Pending" <?= $app['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Approved" <?= $app['status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
        <option value="Rejected" <?= $app['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="admin_remark" class="form-label">Admin Remark (optional for rejection):</label>
      <textarea name="admin_remark" id="admin_remark" class="form-control" rows="3"><?= htmlspecialchars($app['admin_remark']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">💾 Save Status</button>
  </form>
</div></div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
