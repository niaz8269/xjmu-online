<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\OpsHelpers;
use App\Support\Admin\Uploads;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** admin/manage-blogs, create-blog, edit-blog (create + edit), delete-blog */
class BlogsController extends AdminController
{
    use OpsHelpers;

    /** GET /admin/manage-blogs */
    public function index()
    {
        $blogs = $this->rows(DB::select("
            SELECT b.*,
            (SELECT COUNT(*) FROM blogs) as total_posts,
            (SELECT COUNT(*) FROM blogs WHERE status = 'published') as published_posts,
            (SELECT COUNT(*) FROM blogs WHERE status = 'draft') as draft_posts
            FROM blogs b
            ORDER BY b.created_at DESC
        "));

        $stats = [
            'total' => $blogs[0]['total_posts'] ?? 0,
            'published' => $blogs[0]['published_posts'] ?? 0,
            'draft' => $blogs[0]['draft_posts'] ?? 0,
        ];

        return $this->page('admin.blogs.index', 'Manage Blogs', 'manage-blogs', null, [
            'blogs' => $blogs,
            'stats' => $stats,
            'delete_success' => $this->pull('delete_success'),
            'delete_error' => $this->pull('delete_error'),
        ]);
    }

    /** GET /admin/create-blog -> edit-blog (that page handles both create and edit) */
    public function create()
    {
        return redirect('/admin/edit-blog');
    }

    /** GET|POST /admin/edit-blog[?id=] */
    public function edit(Request $request)
    {
        $editing = false;
        $blog = null;
        $blogId = null;

        if ($request->query->has('id')) {
            $editing = true;
            $blogId = (int) $this->strQuery($request, 'id');
            $blog = $this->row(DB::table('blogs')->where('id', $blogId)->first());
            if (! $blog) {
                return $this->plain('Blog post not found');
            }
        }

        $errorMessage = null;
        $successMessage = null;

        if ($request->isMethod('post')) {
            $title = trim($this->post($request, 'title'));
            $slug = trim($this->post($request, 'slug'));
            $excerpt = trim($this->post($request, 'excerpt'));
            $content = $this->post($request, 'content'); // Allow HTML (admin-authored)
            $isFeatured = $request->has('is_featured') ? 1 : 0;
            // Whitelist status so only valid values reach the DB / output (audit #21).
            $postedStatus = $request->input('status');
            $status = in_array($postedStatus, ['draft', 'published', 'archived'], true) ? $postedStatus : 'draft';
            $metaDescription = trim($this->post($request, 'meta_description'));
            $metaKeywords = trim($this->post($request, 'meta_keywords'));

            // Auto-generate slug if empty
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            }

            // Set published_at if publishing
            $publishedAt = null;
            if ($status === 'published') {
                $publishedAt = $editing && $blog['published_at'] ? $blog['published_at'] : date('Y-m-d H:i:s');
            }

            // Image upload — validated (extension + real MIME) like every other upload path.
            $featuredImage = $editing ? $blog['featured_image'] : null;
            try {
                $uploaded = Uploads::save($request, 'featured_image', 'blogs', Uploads::imageExt(), Uploads::imageMime(), 5 * 1024 * 1024);
                if ($uploaded !== null) {
                    $featuredImage = $uploaded;
                }
            } catch (\RuntimeException $e) {
                $errorMessage = $e->getMessage();
            }

            try {
                if ($editing) {
                    DB::table('blogs')->where('id', $blogId)->update([
                        'title' => $title, 'slug' => $slug, 'excerpt' => $excerpt, 'content' => $content,
                        'is_featured' => $isFeatured, 'status' => $status, 'featured_image' => $featuredImage,
                        'published_at' => $publishedAt, 'meta_description' => $metaDescription, 'meta_keywords' => $metaKeywords,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $successMessage = 'Blog post updated successfully!';
                } else {
                    $blogId = DB::table('blogs')->insertGetId([
                        'title' => $title, 'slug' => $slug, 'excerpt' => $excerpt, 'content' => $content,
                        'is_featured' => $isFeatured, 'status' => $status, 'featured_image' => $featuredImage,
                        'published_at' => $publishedAt, 'meta_description' => $metaDescription, 'meta_keywords' => $metaKeywords,
                        'created_at' => date('Y-m-d H:i:s'), // MySQL filled this via a column default
                    ]);
                    $successMessage = 'Blog post created successfully!';
                }

                // Redirect to edit page with success message
                return redirect("/admin/edit-blog?id={$blogId}&success=1");
            } catch (QueryException $e) {
                if ($e->getCode() == 23000) {
                    $errorMessage = 'Error: This slug already exists. Please use a different slug.';
                } else {
                    $errorMessage = 'Error: ' . ($e->getPrevious()?->getMessage() ?? $e->getMessage());
                }
            }
        }

        return $this->page('admin.blogs.edit', ($editing ? 'Edit' : 'Create') . ' Blog Post', 'manage-blogs', null, [
            'editing' => $editing,
            'blog' => $blog,
            'show_success' => $request->query->has('success'),
            'success_message' => $successMessage,
            'error_message' => $errorMessage,
        ]);
    }

    /** POST /admin/delete-blog */
    public function destroy(Request $request)
    {
        // Must be an admin (verified against the DB)
        $role = DB::table('users')->where('id', $request->session()->get('user_id'))->value('role');
        if (! $role || $role !== 'admin') {
            return $this->plain('Access denied. Admin privileges required.', 403);
        }

        // Deletion must be a POST (with a valid CSRF token, enforced by Laravel) (audit #20).
        if (! $request->isMethod('post')) {
            session()->put('delete_error', 'Invalid or expired request. Please use the Delete button.');
            // The original answered 403 *with* a Location header.
            return response('', 403)->header('Location', url('/admin/manage-blogs'));
        }

        $blogId = (int) $this->post($request, 'id', '0');
        if ($blogId <= 0) {
            return redirect('/admin/manage-blogs');
        }

        try {
            // Look up the image first (for cleanup), then delete the row.
            $blog = DB::table('blogs')->where('id', $blogId)->first(['featured_image']);

            if ($blog) {
                DB::table('blogs')->where('id', $blogId)->delete();

                $image = (string) $blog->featured_image;
                if ($image !== '' && ! str_contains($image, '..') && is_file(public_path($image))) {
                    @unlink(public_path($image));
                }
                session()->put('delete_success', 'Blog post deleted successfully!');
            }
        } catch (QueryException $e) {
            Log::error('delete-blog: ' . $e->getMessage());
            session()->put('delete_error', 'Error deleting blog post. Please try again.');
        }

        return redirect('/admin/manage-blogs');
    }
}
