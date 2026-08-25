<?php
require_once __DIR__ . '/includes/auth.php';

// Pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20; // Records per page
$offset = ($page - 1) * $perPage;

// Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = '';
$searchParams = [];

if (!empty($search)) {
    $searchCondition = "WHERE full_name LIKE :search1 OR email LIKE :search2";
    $searchTerm = "%{$search}%";
    $searchParams = [':search1' => $searchTerm, ':search2' => $searchTerm];
}

// Get total count for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM users {$searchCondition}");
$countStmt->execute($searchParams);
$totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $perPage);

// Fetch paginated users
$stmt = $pdo->prepare("
    SELECT id, full_name, email, role, created_at
    FROM users 
    {$searchCondition}
    ORDER BY created_at DESC 
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
$admin_page_title = 'Registered Users';
$admin_active     = 'users';
require __DIR__ . '/includes/layout_top.php';
?>

<div class="card"><div class="card-body">
  <?php if ($is_superadmin): ?>
  <a href="manage_users" class="btn btn-outline-secondary btn-sm mb-3">Manage Users (promote / demote)</a>
  <?php endif; ?>

  <!-- Search Box -->
  <div class="mb-3">
    <form method="GET" class="d-flex gap-2">
      <input type="text" name="search" class="form-control" placeholder="🔍 Search by name or email..." value="<?= htmlspecialchars($search) ?>" style="max-width: 400px;">
      <button type="submit" class="btn btn-primary">Search</button>
      <?php if (!empty($search)): ?>
        <a href="users" class="btn btn-secondary">Clear</a>
      <?php endif; ?>
    </form>
  </div>
  
  <!-- Results Info -->
  <div class="mb-3">
    <small class="text-muted">
      Showing <?= count($users) ?> of <?= $totalRecords ?> users
      <?php if (!empty($search)): ?>
        (filtered results)
      <?php endif; ?>
    </small>
  </div>

  <?php if (empty($users)): ?>
    <div class="alert alert-info">
      <h5>No users found</h5>
      <p>There are no users<?= !empty($search) ? ' matching your search criteria' : ' in the system' ?>.</p>
      <?php if (!empty($search)): ?>
        <a href="users" class="btn btn-primary">Clear Search</a>
      <?php endif; ?>
    </div>
  <?php else: ?>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Registered At</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['full_name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= $user['role'] ?></td>
        <td><?= isset($user['created_at']) ? $user['created_at'] : '—' ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
  
  <!-- Pagination -->
  <?php if (!empty($users) && $totalPages > 1): ?>
    <div class="mt-4">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">Previous</a>
            </li>
          <?php endif; ?>
          
          <?php
          $startPage = max(1, $page - 2);
          $endPage = min($totalPages, $page + 2);
          
          for ($i = $startPage; $i <= $endPage; $i++):
          ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          
          <?php if ($page < $totalPages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">Next</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  <?php endif; ?>
</div></div>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
