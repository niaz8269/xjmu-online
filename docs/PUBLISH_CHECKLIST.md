# Publish checklist for the portal + admin rebuild (one publish at the end, as the owner asked)

1. Back up the live database (mysqldump to ~/backups) before anything else.
2. Deploy the code, then run each NEW migration file on its own with
   `php83 artisan migrate --force --path=database/migrations/<file>.php`. NEVER run a plain `artisan migrate` on the live
   site: the live database was imported from the old site and has no history of the older migrations, so a plain
   migrate would try to recreate existing tables. (Done 22 Nov 2026: crm_leads, crm_lead_notes, crm_application_log,
   cms_overrides.)
3. Owner approved the two applicant emails on 22 Nov 2026: add `PORTAL_NOTIFY_STUDENTS=true` to the live `.env`,
   then `php83 artisan config:cache`.
4. Purge the CDN, then check: student dashboard, application form, PDF/ZIP download, admin Leads, Applications,
   an application page, exports.
5. Remake the template copy in E:\Work\university-site-template from the published version.

Published 22 Nov 2026 (commit 4927b8c). Backup: ~/backups/db-before-admin-rebuild-20261121-203049.sql.gz on the server.
