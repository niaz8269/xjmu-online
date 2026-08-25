<?php
require_once __DIR__ . '/includes/auth.php';

try {
// Pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20; // Records per page
$offset = ($page - 1) * $perPage;

// Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = '';
$searchParams = [];

if (!empty($search)) {
    $searchCondition = "AND (u.full_name LIKE :search1 OR u.email LIKE :search2)";
    $searchTerm = "%{$search}%";
    $searchParams = [':search1' => $searchTerm, ':search2' => $searchTerm];
}

// Submission status filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
if (!in_array($filter, ['submitted', 'unsubmitted', ''], true)) {
    $filter = '';
}
$filterCondition = '';
if ($filter === 'submitted') {
    $filterCondition = "AND (a.is_submitted = 1 OR a.status IN ('Pending','Approved','Rejected','Submitted'))";
} elseif ($filter === 'unsubmitted') {
    $filterCondition = "AND (a.user_id IS NULL OR (COALESCE(a.is_submitted,0) != 1 AND (a.status IS NULL OR a.status NOT IN ('Pending','Approved','Rejected','Submitted'))))";
}

// Summary counts for filter buttons (always over full dataset, ignoring search)
$summaryStmt = $pdo->query("
    SELECT
        SUM(CASE WHEN a.is_submitted = 1 OR a.status IN ('Pending','Approved','Rejected','Submitted') THEN 1 ELSE 0 END) AS submitted_count,
        SUM(CASE WHEN a.user_id IS NULL OR (COALESCE(a.is_submitted,0) != 1 AND (a.status IS NULL OR a.status NOT IN ('Pending','Approved','Rejected','Submitted'))) THEN 1 ELSE 0 END) AS unsubmitted_count,
        COUNT(u.id) AS all_count
    FROM users u
    LEFT JOIN applications a ON u.id = a.user_id
");
$summary        = $summaryStmt->fetch(PDO::FETCH_ASSOC);
$countAll        = (int)($summary['all_count'] ?? 0);
$countSubmitted  = (int)($summary['submitted_count'] ?? 0);
$countUnsub      = (int)($summary['unsubmitted_count'] ?? 0);

// Get total count for pagination (respects both search + filter)
$countStmt = $pdo->prepare("
    SELECT COUNT(*) as total
    FROM users u
    LEFT JOIN applications a ON u.id = a.user_id
    WHERE 1=1 {$searchCondition} {$filterCondition}
");
$countStmt->execute($searchParams);
$totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $perPage);

// Fetch paginated users and their submission status
$stmt = $pdo->prepare("
    SELECT u.id, u.full_name, u.email,
           a.is_submitted, a.submitted_at,
           a.status, a.admin_remark
    FROM users u
    LEFT JOIN applications a ON u.id = a.user_id
    WHERE 1=1 {$searchCondition} {$filterCondition}
    ORDER BY u.created_at DESC
    LIMIT :limit OFFSET :offset
");

// Bind all parameters
$params = array_merge($searchParams, [':limit' => $perPage, ':offset' => $offset]);
foreach ($params as $key => $value) {
    if ($key === ':limit' || $key === ':offset') {
        $stmt->bindValue($key, $value, PDO::PARAM_INT);
    } else {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
}
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// FIX N+1 QUERY PROBLEM: Batch load all related data
$userIds = array_column($users, 'id');
$personalData = [];
$educationData = [];
$documentsData = [];
$degreeMajorData = [];

if (!empty($userIds)) {
    // Batch load personal info
    $placeholders = str_repeat('?,', count($userIds) - 1) . '?';
    $personalStmt = $pdo->prepare("SELECT * FROM personal_info WHERE user_id IN ($placeholders)");
    $personalStmt->execute($userIds);
    $personalRows = $personalStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($personalRows as $row) {
        $personalData[$row['user_id']] = $row;
    }
    
    // Batch load education records
    $eduStmt = $pdo->prepare("SELECT * FROM education_records WHERE user_id IN ($placeholders)");
    $eduStmt->execute($userIds);
    $eduRows = $eduStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($eduRows as $row) {
        if (!isset($educationData[$row['user_id']])) {
            $educationData[$row['user_id']] = [];
        }
        $educationData[$row['user_id']][] = $row;
    }
    
    // Batch load documents
    $docsStmt = $pdo->prepare("SELECT * FROM documents WHERE user_id IN ($placeholders)");
    $docsStmt->execute($userIds);
    $docsRows = $docsStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($docsRows as $row) {
        if (!isset($documentsData[$row['user_id']])) {
            $documentsData[$row['user_id']] = [];
        }
        $documentsData[$row['user_id']][] = $row;
    }
    
    // Degree/Major (optional columns - may not exist until install_application_degree_major.sql is run)
    $degreeMajorData = [];
    try {
        $dmStmt = $pdo->prepare("SELECT user_id, degree_applying_for, major_applying_for FROM applications WHERE user_id IN ($placeholders)");
        $dmStmt->execute($userIds);
        foreach ($dmStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $degreeMajorData[$row['user_id']] = $row;
        }
    } catch (PDOException $e) {
        // Columns may not exist yet
    }
}

} catch (PDOException $e) {
    // Log the error and show user-friendly message
    error_log("Database error in applications.php: " . $e->getMessage());
    die("
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='icon' href='../images/favicon.ico' type='image/x-icon'>
</head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4>Database Error</h4>
                <p>Unable to load applications. Please check the database connection and try again.</p>
                <a href='dashboard' class='btn btn-secondary'>Back to Dashboard</a>
            </div>
        </div>
    </body>
    </html>
    ");
}

$admin_page_title = 'All Applications';
$admin_active     = 'applications';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if (isset($_SESSION['flash'])): ?>
  <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
<?php endif; ?>

<div class="card"><div class="card-body">

  <!-- Filter Tabs + Search -->
  <div class="card mb-4 border-0 shadow-sm">
    <div class="card-body pb-2">

      <!-- Submission status filter tabs -->
      <div class="btn-group mb-3" role="group" aria-label="Filter by submission status">
        <a href="?<?= !empty($search) ? 'search='.urlencode($search).'&' : '' ?>filter="
          class="btn btn-sm <?= $filter === '' ? 'btn-dark' : 'btn-outline-secondary' ?>">
          All <span class="badge <?= $filter === '' ? 'bg-light text-dark' : 'bg-secondary' ?> ms-1"><?= $countAll ?></span>
        </a>
        <a href="?<?= !empty($search) ? 'search='.urlencode($search).'&' : '' ?>filter=submitted"
          class="btn btn-sm <?= $filter === 'submitted' ? 'btn-success' : 'btn-outline-success' ?>">
          Submitted <span class="badge <?= $filter === 'submitted' ? 'bg-light text-success' : 'bg-success' ?> ms-1"><?= $countSubmitted ?></span>
        </a>
        <a href="?<?= !empty($search) ? 'search='.urlencode($search).'&' : '' ?>filter=unsubmitted"
          class="btn btn-sm <?= $filter === 'unsubmitted' ? 'btn-warning text-dark' : 'btn-outline-warning' ?>">
          Unsubmitted <span class="badge <?= $filter === 'unsubmitted' ? 'bg-light text-warning' : 'bg-warning text-dark' ?> ms-1"><?= $countUnsub ?></span>
        </a>
      </div>

      <!-- Search box -->
      <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
        <?php if (!empty($filter)): ?>
          <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
        <?php endif; ?>
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="Search by name or email…"
               value="<?= htmlspecialchars($search) ?>" style="max-width: 300px;">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        <?php if (!empty($search) || !empty($filter)): ?>
          <a href="applications" class="btn btn-outline-secondary btn-sm">Reset all</a>
        <?php endif; ?>
      </form>

    </div>
  </div>

  <!-- Results Info -->
  <div class="mb-3">
    <small class="text-muted">
      Showing <?= count($users) ?> of <?= $totalRecords ?>
      <?php if ($filter === 'submitted'): ?>
        submitted
      <?php elseif ($filter === 'unsubmitted'): ?>
        unsubmitted
      <?php endif; ?>
      application<?= $totalRecords !== 1 ? 's' : '' ?>
      <?php if (!empty($search)): ?>
        matching <strong><?= htmlspecialchars($search) ?></strong>
      <?php endif; ?>
    </small>
  </div>

  <?php if (empty($users)): ?>
    <div class="alert alert-info">
      <h5>No applications found</h5>
      <p>
        <?php if (!empty($search) && !empty($filter)): ?>
          No <?= $filter ?> applications matching <strong><?= htmlspecialchars($search) ?></strong>.
        <?php elseif (!empty($search)): ?>
          No applications matching <strong><?= htmlspecialchars($search) ?></strong>.
        <?php elseif (!empty($filter)): ?>
          There are currently no <?= $filter ?> applications.
        <?php else: ?>
          There are currently no user applications in the system.
        <?php endif; ?>
      </p>
      <?php if (!empty($search) || !empty($filter)): ?>
        <a href="applications" class="btn btn-primary btn-sm">Reset all filters</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
  <div class="accordion" id="applicationsAccordion">
    <?php foreach ($users as $index => $user): ?>
      <?php
        $statusText = trim((string)($user['status'] ?? ''));
        $isSubmitted = !empty($user['is_submitted']) || in_array($statusText, ['Pending', 'Approved', 'Rejected', 'Submitted'], true);
      ?>
      <div class="accordion-item mb-2">
        <h2 class="accordion-header" id="heading<?= $index ?>">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
            <?= htmlspecialchars($user['full_name']) ?> (<?= htmlspecialchars($user['email']) ?>)
            - <span class="badge bg-<?= $isSubmitted ? 'success' : 'warning' ?> ms-2">
                <?= $isSubmitted ? 'Submitted' : 'Unsubmitted' ?>
              </span>
          </button>
        </h2>
        <div id="collapse<?= $index ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index ?>" data-bs-parent="#applicationsAccordion">
          <div class="accordion-body">

            <?php
              $dm = $degreeMajorData[$user['id']] ?? null;
              if ($dm && (!empty($dm['degree_applying_for']) || !empty($dm['major_applying_for']))):
            ?>
            <p><strong>Degree Applying for:</strong> <?= htmlspecialchars($dm['degree_applying_for'] ?? '—') ?><br>
            <strong>Major Applying for:</strong> <?= htmlspecialchars($dm['major_applying_for'] ?? '—') ?></p>
            <hr>
            <?php endif; ?>

            <!-- Application Status & Review -->
            <p><strong>Status:</strong>
            <?= !empty($user['status']) ? htmlspecialchars($user['status']) : 'Not set' ?>
               <?php if (!empty($user['status']) && $user['status'] === 'Rejected' && !empty($user['admin_remark'])): ?>
                  <br><small class="text-danger"><?= htmlspecialchars($user['admin_remark']) ?></small>
              <?php endif; ?>
              </p>
            <a href="review_application?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary mb-3">🔍 Review</a>

            <!-- Buttons -->
            <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
              <form action="delete_application" method="POST" onsubmit="return confirm('Are you sure you want to delete this application?');" class="d-inline">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm" aria-label="Delete application for <?= htmlspecialchars($user['full_name']) ?>">🗑️ Delete</button>
              </form>
              <a href="export_pdf?user_id=<?= $user['id'] ?>" class="btn btn-success btn-sm">📄 Export as PDF</a>
              <a href="download_application?user_id=<?= $user['id'] ?>" class="btn btn-success btn-sm">📦 Download Application Package</a>
            </div>

            <!-- Sections - Using pre-loaded data (no N+1 queries!) -->
            <strong>Personal Information:</strong><br>
            <?php
              $personal = $personalData[$user['id']] ?? null;
              if ($personal) {
                  foreach ($personal as $key => $value) {
                      if (!in_array($key, ['id', 'user_id', 'created_at'])) {
                          echo "<strong>" . ucfirst(str_replace('_', ' ', $key)) . ":</strong> " . htmlspecialchars($value) . "<br>";
                      }
                  }
              } else {
                  echo "<em>No personal info provided.</em><br>";
              }
            ?>

            <hr>

            <strong>Education Records:</strong><br>
            <?php
              $edu_records = $educationData[$user['id']] ?? [];
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
                  echo "<em>No education records found.</em><br>";
              }
            ?>

            <hr>

            <strong>Uploaded Documents:</strong><br>
            <?php
              $files = $documentsData[$user['id']] ?? [];
              if ($files) {
                  echo "<ul>";
                  foreach ($files as $doc) {
                      echo "<li><a href='../" . htmlspecialchars($doc['file_path']) . "' target='_blank' rel='noopener noreferrer'>" . htmlspecialchars($doc['file_name']) . "</a></li>";
                  }
                  echo "</ul>";
              } else {
                  echo "<em>No documents uploaded.</em><br>";
              }
            ?>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  
  <!-- Pagination -->
  <?php
  // Build a reusable query-string fragment that preserves search + filter
  $paginationParams = '';
  if (!empty($search))  $paginationParams .= '&search=' . urlencode($search);
  if (!empty($filter))  $paginationParams .= '&filter=' . urlencode($filter);
  ?>
  <?php if (!empty($users) && $totalPages > 1): ?>
    <div class="mt-4">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page - 1 . $paginationParams ?>">Previous</a>
            </li>
          <?php endif; ?>

          <?php
          $startPage = max(1, $page - 2);
          $endPage   = min($totalPages, $page + 2);
          for ($i = $startPage; $i <= $endPage; $i++):
          ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i . $paginationParams ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $totalPages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page + 1 . $paginationParams ?>">Next</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  <?php endif; ?>
</div></div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
