<?php
require_once __DIR__ . '/includes/auth.php';

// Handle status update
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $message = 'Security validation failed. Please try again.';
    } else {
    $userId = (int)$_POST['user_id'];
    $status = trim($_POST['status'] ?? '');

    try {
        // Update the remark ONLY on an existing application — never INSERT, which
        // would fabricate a Pending/draft applicant from an admin note (audit #17).
        $stmt = $pdo->prepare("UPDATE applications SET admin_remark = :admin_remark WHERE user_id = :user_id");
        $stmt->execute([
            ':admin_remark' => $status,
            ':user_id'      => $userId,
        ]);
        $message = $stmt->rowCount() > 0
            ? 'Remark updated successfully.'
            : 'No application exists for this user, so nothing was updated.';
    } catch (PDOException $e) {
        $message = 'Failed to update remark. Please try again.';
    }
    }
}

// Pagination setup
$perPage = 30;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Count total applications
$countSql = "SELECT COUNT(*) FROM applications";
$totalApplications = (int)$pdo->query($countSql)->fetchColumn();
$totalPages = max(1, ceil($totalApplications / $perPage));
$offset = ($page - 1) * $perPage;

// Fetch paginated applications
try {
    $sql = "
        SELECT 
            a.user_id AS id,
            u.full_name,
            u.email,
            p.tel AS phone,
            a.degree_applying_for,
            a.major_applying_for,
            a.is_submitted,
            a.status,
            a.admin_remark
        FROM applications a
        LEFT JOIN users u ON u.id = a.user_id
        LEFT JOIN personal_info p ON p.user_id = a.user_id
        ORDER BY a.submitted_at DESC, a.user_id DESC
        LIMIT :perPage OFFSET :offset
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $sql = "
        SELECT 
            a.user_id AS id,
            u.full_name,
            u.email,
            p.tel AS phone,
            a.degree_applying_for,
            a.major_applying_for,
            a.is_submitted,
            a.status,
            a.admin_remark
        FROM applications a
        LEFT JOIN users u ON u.id = a.user_id
        LEFT JOIN personal_info p ON p.user_id = a.user_id
        ORDER BY a.user_id DESC
        LIMIT :perPage OFFSET :offset
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$admin_page_title = 'Manage Applications';
$admin_active     = 'manage-applications';
require __DIR__ . '/includes/layout_top.php';
?>

<style>
        html,
        body {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .applications-wrap .table {
            width: 100%;
            table-layout: auto;
        }

        .applications-wrap .table th,
        .applications-wrap .table td {
            white-space: normal;
            word-break: break-word;
            vertical-align: middle;
        }

        .applications-wrap .table th.col-index,
        .applications-wrap .table td.col-index,
        .applications-wrap .table th.col-actions,
        .applications-wrap .table td.col-actions,
        .applications-wrap .table th.col-save,
        .applications-wrap .table td.col-save {
            width: 1%;
            white-space: nowrap;
            word-break: normal;
        }

        .status-form {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .status-form .status-input {
            min-width: 0;
            width: 100%;
            flex: 1 1 260px;
        }

        .col-save .btn {
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 991.98px) {
            .applications-wrap .table {
                font-size: 0.9rem;
            }
        }
    </style>
<div class="card applications-wrap"><div class="card-body">

    <?php if (!empty($message)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($applications)): ?>
        <div class="alert alert-info">
            <h5>No applications found</h5>
            <p>There are currently no applications in the system.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="col-index">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Degree</th>
                        <th>Major</th>
                        <th>Submission</th>
                        <th>Status (editable)</th>
                        <th class="col-save">Save</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $index => $row): ?>
                        <tr>
                            <td class="col-index"><?= ($offset + $index + 1) ?></td>
                            <td><?= htmlspecialchars($row['full_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['degree_applying_for'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['major_applying_for'] ?? '') ?></td>
                            <td>
                                <?php $submitted = !empty($row['is_submitted']) || in_array((string)($row['status'] ?? ''), ['Pending', 'Approved', 'Rejected', 'Submitted'], true); ?>
                                <span class="badge bg-<?= $submitted ? 'success' : 'warning' ?>">
                                    <?= $submitted ? 'Submitted' : 'Unsubmitted' ?>
                                </span>
                            </td>
                            <td>
                                <form method="post" id="status-form-<?= (int)$row['id'] ?>" class="status-form">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="user_id" value="<?= (int)$row['id'] ?>">
                                    <input 
                                        type="text" 
                                        name="status" 
                                        class="form-control status-input"
                                        value="<?= htmlspecialchars($row['admin_remark'] ?? '') ?>" 
                                        placeholder="Enter any internal admin status/note">
                                </form>
                            </td>
                            <td class="col-save text-center">
                                <button type="submit" form="status-form-<?= (int)$row['id'] ?>" class="btn btn-sm btn-primary">Save</button>
                            </td>
                            <td class="col-actions">
                                <a href="review_application?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                    Review
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination Controls -->
        <nav aria-label="Applications pagination">
            <ul class="pagination justify-content-center mt-4">
                <li class="page-item<?= ($page <= 1 ? ' disabled' : '') ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>" tabindex="-1">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item<?= ($i == $page ? ' active' : '') ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item<?= ($page >= $totalPages ? ' disabled' : '') ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div></div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>

