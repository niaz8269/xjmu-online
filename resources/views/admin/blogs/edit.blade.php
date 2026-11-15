@extends('admin.layout')
@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .header {
            background: white;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .header h1 {
            color: #e72128;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-secondary {
            background: #666;
            color: white;
        }

        .btn-secondary:hover {
            background: #444;
        }

        .form-container {
            background: white;
            padding: 35px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group label .required {
            color: #e72128;
        }

        .form-group input[type="text"],
        .form-group input[type="file"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #e72128;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-group .help-text {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .btn-primary {
            background: #e72128;
            color: white;
            padding: 15px 40px;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            background: #cc0000;
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #e9ecef;
        }

        .editor-toolbar {
            background: #f8f9fa;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-bottom: none;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .editor-btn {
            padding: 5px 10px;
            background: white;
            border: 1px solid #ddd;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .editor-btn:hover {
            background: #e9ecef;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-container {
                padding: 20px;
            }
        }
    </style>

        <div class="form-container">
            @if ($show_success)
                <div class="alert alert-success">
                    âœ” Blog post saved successfully! <a href=”../blog/{!! rawurlencode($blog['slug'] ?? '') !!}” target=”_blank” rel=”noopener noreferrer”>View Post</a>
                </div>
            @endif

            @if (!is_null($success_message))
                <div class="alert alert-success">{!! $success_message !!}</div>
            @endif

            @if (!is_null($error_message))
                <div class="alert alert-error">{!! $error_message !!}</div>
            @endif

            <form method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label>Title <span class="required">*</span></label>
                        <input type="text" name="title" required value="{{ $blog['title'] ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label>URL Slug <span class="required">*</span></label>
                        <input type="text" name="slug" value="{{ $blog['slug'] ?? '' }}">
                        <div class="help-text">Leave empty to auto-generate from title</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Excerpt <span class="required">*</span></label>
                    <textarea name="excerpt" required>{{ $blog['excerpt'] ?? '' }}</textarea>
                    <div class="help-text">Brief summary (150-200 characters recommended)</div>
                </div>

                <div class="form-group">
                    <label>Content <span class="required">*</span></label>
                    <div class="editor-toolbar">
                        <button type="button" class="editor-btn" onclick="insertTag('h2')">H2</button>
                        <button type="button" class="editor-btn" onclick="insertTag('h3')">H3</button>
                        <button type="button" class="editor-btn" onclick="insertTag('p')">P</button>
                        <button type="button" class="editor-btn" onclick="insertTag('strong')">Bold</button>
                        <button type="button" class="editor-btn" onclick="insertTag('em')">Italic</button>
                        <button type="button" class="editor-btn" onclick="insertList('ul')">â€¢ List</button>
                        <button type="button" class="editor-btn" onclick="insertList('ol')">1. List</button>
                        <button type="button" class="editor-btn" onclick="insertLink()">Link</button>
                    </div>
                    <textarea name="content" id="content" required style="min-height: 400px; font-family: 'Courier New', monospace;">{{ $blog['content'] ?? '' }}</textarea>
                    <div class="help-text">HTML tags allowed. Use the toolbar buttons for common formatting.</div>
                </div>

                <div class="form-row">

                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select name="status" required>
                            <option value="draft" {!! (isset($blog['status']) && $blog['status'] == 'draft') ? 'selected' : '' !!}>Draft</option>
                            <option value="published" {!! (isset($blog['status']) && $blog['status'] == 'published') ? 'selected' : '' !!}>Published</option>
                            <option value="archived" {!! (isset($blog['status']) && $blog['status'] == 'archived') ? 'selected' : '' !!}>Archived</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Featured Image</label>
                    <input type="file" name="featured_image" accept="image/*">
                    @if (isset($blog['featured_image']) && $blog['featured_image'])
                        <div class="help-text">Current: {{ $blog['featured_image'] }}</div>
                    @endif
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {!! (isset($blog['is_featured']) && $blog['is_featured']) ? 'checked' : '' !!}>
                    <label for="is_featured" style="margin: 0;">â­ Feature this post (shows in featured section)</label>
                </div>

                <div class="form-group">
                    <label>Meta Description (SEO)</label>
                    <textarea name="meta_description" rows="2">{{ $blog['meta_description'] ?? '' }}</textarea>
                    <div class="help-text">For search engines (160 characters max)</div>
                </div>

                <div class="form-group">
                    <label>Meta Keywords (SEO)</label>
                    <input type="text" name="meta_keywords" value="{{ $blog['meta_keywords'] ?? '' }}">
                    <div class="help-text">Comma-separated keywords</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        {!! $editing ? 'ðŸ’¾ Update Blog Post' : 'âœ“ Create Blog Post' !!}
                    </button>
                    <a href="manage-blogs" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

    <script>
        function insertTag(tag) {
            const textarea = document.getElementById('content');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            const replacement = `<${tag}>${selectedText || 'Text here'}</${tag}>`;
            
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            textarea.focus();
            textarea.setSelectionRange(start + tag.length + 2, start + replacement.length - tag.length - 3);
        }

        function insertList(type) {
            const textarea = document.getElementById('content');
            const start = textarea.selectionStart;
            const list = `<${type}>\n    <li>Item 1</li>\n    <li>Item 2</li>\n    <li>Item 3</li>\n</${type}>`;
            
            textarea.value = textarea.value.substring(0, start) + list + textarea.value.substring(start);
            textarea.focus();
        }

        function insertLink() {
            const url = prompt('Enter URL:');
            if (url) {
                const textarea = document.getElementById('content');
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const selectedText = textarea.value.substring(start, end);
                const link = `<a href="${url}">${selectedText || 'Link text'}</a>`;
                
                textarea.value = textarea.value.substring(0, start) + link + textarea.value.substring(end);
                textarea.focus();
            }
        }

        // Auto-generate slug from title
        document.querySelector('input[name="title"]').addEventListener('blur', function() {
            const slugInput = document.querySelector('input[name="slug"]');
            if (!slugInput.value) {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });
    </script>

@endsection
