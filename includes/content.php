<?php
/**
 * Public-side readers for the admin-managed content collections.
 * Each returns published rows ordered for display (empty array on error).
 */
require_once __DIR__ . '/../db.php';

if (!function_exists('content_rows')) {
    function content_rows($sql, $params = []) {
        try {
            $pdo = $GLOBALS['pdo'];
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            error_log('content_rows: ' . $e->getMessage());
            return [];
        }
    }

    function get_programs()     { return content_rows("SELECT * FROM programs       WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_scholarships() { return content_rows("SELECT * FROM scholarships   WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_downloads()    { return content_rows("SELECT * FROM downloads      WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_gallery()      { return content_rows("SELECT * FROM gallery        WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_faqs()         { return content_rows("SELECT * FROM faqs           WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_consultants()  { return content_rows("SELECT * FROM consultants    WHERE is_published=1 ORDER BY sort_order, id"); }

    /** Testimonials, optionally filtered by category ('student' | 'alumni'). */
    function get_testimonials($category = null) {
        if ($category !== null && $category !== '') {
            return content_rows("SELECT * FROM testimonials WHERE is_published=1 AND category=? ORDER BY sort_order, id", [$category]);
        }
        return content_rows("SELECT * FROM testimonials WHERE is_published=1 ORDER BY sort_order, id");
    }

    /** Sections of an editable page, keyed by section_key. */
    function get_page_sections($pageKey) {
        return content_rows("SELECT * FROM page_sections WHERE page_key=? AND is_published=1 ORDER BY sort_order, id", [$pageKey]);
    }

    /** Homepage card collections (admin-managed). */
    function get_home_programs()       { return content_rows("SELECT * FROM home_programs       WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_home_why()            { return content_rows("SELECT * FROM home_why            WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_home_steps()          { return content_rows("SELECT * FROM home_steps          WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_home_accreditations() { return content_rows("SELECT * FROM home_accreditations WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_home_facts()          { return content_rows("SELECT * FROM home_facts          WHERE is_published=1 ORDER BY sort_order, id"); }
    function get_mbbs_curriculum()     { return content_rows("SELECT * FROM mbbs_curriculum     WHERE is_published=1 ORDER BY sort_order, id"); }

    /** Upcoming/recent published events for the homepage events section. */
    function get_latest_events($limit = 3) {
        $limit = max(1, (int) $limit);
        try {
            return content_rows(
                "SELECT id, title, slug, excerpt, event_date, event_time_start, venue, featured_image
                 FROM events WHERE status = 'published'
                 ORDER BY event_date DESC, id DESC
                 LIMIT $limit"
            );
        } catch (\Throwable $e) { return []; }
    }

    /** Latest published blog posts for the homepage news grid. */
    function get_latest_blogs($limit = 3) {
        $limit = max(1, (int) $limit);
        return content_rows(
            "SELECT id, title, slug, excerpt, featured_image, published_at, created_at
             FROM blogs WHERE status = 'published'
             ORDER BY COALESCE(published_at, created_at) DESC
             LIMIT $limit"
        );
    }
}
