<?php
require_once __DIR__ . '/includes/auth.php';

// Get all blog posts
$sql = "
    SELECT b.*,
    (SELECT COUNT(*) FROM blogs) as total_posts,
    (SELECT COUNT(*) FROM blogs WHERE status = 'published') as published_posts,
    (SELECT COUNT(*) FROM blogs WHERE status = 'draft') as draft_posts
    FROM blogs b
    ORDER BY b.created_at DESC
";
$blogs = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$stats = [
    'total' => $blogs[0]['total_posts'] ?? 0,
    'published' => $blogs[0]['published_posts'] ?? 0,
    'draft' => $blogs[0]['draft_posts'] ?? 0
];

$admin_page_title = 'Manage Blogs';
$admin_active     = 'manage-blogs';
require __DIR__ . '/includes/layout_top.php';
?>
<style>
    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        text-align: center;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: #e72128;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 1.1rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table-container {
        background: white;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e9ecef;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    tr:hover {
        background: #f8f9fa;
    }

    .status-badge {
        padding: 5px 12px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        border-radius: 3px;
    }

    .status-published {
        background: #28a745;
        color: white;
    }

    .status-draft {
        background: #ffc107;
        color: #333;
    }

    .status-archived {
        background: #6c757d;
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-small {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .btn-edit {
        background: #007bff;
        color: white;
    }

    .btn-edit:hover {
        background: #0056b3;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .btn-view {
        background: #17a2b8;
        color: white;
    }

    .btn-view:hover {
        background: #117a8b;
    }

    .no-posts {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .no-posts h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
</style>

<div class="card"><div class="card-body">
    <div class="d-flex justify-content-end mb-3">
        <a href="create-blog" class="btn btn-primary">+ Create New Blog</a>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['total']; ?></div>
            <div class="stat-label">Total Posts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['published']; ?></div>
            <div class="stat-label">Published</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats['draft']; ?></div>
            <div class="stat-label">Drafts</div>
        </div>
    </div>

    <div class="table-container">
        <?php if (isset($_SESSION['delete_success'])): ?>
            <div style="padding: 15px; background: #d4edda; color: #155724; margin-bottom: 20px; border-radius: 4px;">
                &#10003; <?php echo $_SESSION['delete_success']; unset($_SESSION['delete_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['delete_error'])): ?>
            <div style="padding: 15px; background: #f8d7da; color: #721c24; margin-bottom: 20px; border-radius: 4px;">
                &#10007; <?php echo $_SESSION['delete_error']; unset($_SESSION['delete_error']); ?>
            </div>
        <?php endif; ?>

        <h2 style="margin-bottom: 20px;">All Blog Posts</h2>

        <?php if (empty($blogs)): ?>
            <div class="no-posts">
                <h3>No blog posts yet</h3>
                <p>Create your first blog post to get started!</p>
                <a href="create-blog" class="btn btn-primary" style="margin-top: 20px;">Create Blog Post</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Views</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blogs as $blog): ?>
                        <tr>
                            <td><?php echo $blog['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($blog['title']); ?></strong>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo e($blog['status']); ?>">
                                    <?php echo e(ucfirst($blog['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo $blog['is_featured'] ? '&#11088; Yes' : 'No'; ?></td>
                            <td><?php echo number_format($blog['views']); ?></td>
                            <td><?php echo $blog['published_at'] ? date('M j, Y', strtotime($blog['published_at'])) : 'Not published'; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit-blog?id=<?php echo $blog['id']; ?>" class="btn btn-edit btn-small">Edit</a>
                                    <a href="../blog-post?slug=<?php echo urlencode($blog['slug']); ?>" class="btn btn-view btn-small" target="_blank" rel="noopener noreferrer">View</a>
                                    <form action="delete-blog" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo (int)$blog['id']; ?>">
                                        <button type="submit" class="btn btn-delete btn-small">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div></div>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
