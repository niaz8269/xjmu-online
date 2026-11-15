@extends('admin.layout')
@section('content')
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
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Posts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['published'] }}</div>
            <div class="stat-label">Published</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['draft'] }}</div>
            <div class="stat-label">Drafts</div>
        </div>
    </div>

    <div class="table-container">
        @if (!is_null($delete_success))
            <div style="padding: 15px; background: #d4edda; color: #155724; margin-bottom: 20px; border-radius: 4px;">
                &#10003; {!! $delete_success !!}
            </div>
        @endif

        @if (!is_null($delete_error))
            <div style="padding: 15px; background: #f8d7da; color: #721c24; margin-bottom: 20px; border-radius: 4px;">
                &#10007; {!! $delete_error !!}
            </div>
        @endif

        <h2 style="margin-bottom: 20px;">All Blog Posts</h2>

        @if (empty($blogs))
            <div class="no-posts">
                <h3>No blog posts yet</h3>
                <p>Create your first blog post to get started!</p>
                <a href="create-blog" class="btn btn-primary" style="margin-top: 20px;">Create Blog Post</a>
            </div>
        @else
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
                    @foreach ($blogs as $blog)
                        <tr>
                            <td>{!! $blog['id'] !!}</td>
                            <td>
                                <strong>{{ $blog['title'] }}</strong>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $blog['status'] }}">
                                    {{ ucfirst($blog['status']) }}
                                </span>
                            </td>
                            <td>{!! $blog['is_featured'] ? '&#11088; Yes' : 'No' !!}</td>
                            <td>{{ number_format($blog['views']) }}</td>
                            <td>{{ $blog['published_at'] ? date('M j, Y', strtotime($blog['published_at'])) : 'Not published' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit-blog?id={!! $blog['id'] !!}" class="btn btn-edit btn-small">Edit</a>
                                    <a href="../blog-post?slug={!! urlencode($blog['slug']) !!}" class="btn btn-view btn-small" target="_blank" rel="noopener noreferrer">View</a>
                                    <form action="delete-blog" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ (int)$blog['id'] }}">
                                        <button type="submit" class="btn btn-delete btn-small">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div></div>
@endsection
