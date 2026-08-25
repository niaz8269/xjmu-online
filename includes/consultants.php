<?php
require_once __DIR__ . '/content.php';
require_once __DIR__ . '/settings.php';
$__consultants = get_consultants();
if ($__consultants):
    $__url = function ($p) { return function_exists('get_absolute_url') ? get_absolute_url($p) : $p; };
    $__site = get_setting('site_name', 'XJMU');
?>
<!-- Admission Consultants — WhatsApp-style chat widget (admin-managed) -->
<div class="chatw" id="chatWidget">

    <!-- Step 1: list of counsellors -->
    <div class="chatw__panel" id="chatPanel" role="dialog" aria-label="Admission counseling" aria-hidden="true">
        <div class="chatw__head">
            <button class="chatw__close" type="button" data-chat-close aria-label="Close">&times;</button>
            <p><?= htmlspecialchars($__site) ?>'s admission counseling team is here to answer your queries!</p>
        </div>
        <div class="chatw__list">
            <?php foreach ($__consultants as $__i => $__c): ?>
            <button class="chatw__agent" type="button" data-agent="<?= (int) $__i ?>">
                <span class="chatw__avatar">
                    <img src="<?= htmlspecialchars($__url($__c['photo'] ?: 'images/logo.png'), ENT_QUOTES) ?>" alt="<?= htmlspecialchars($__c['name']) ?>" loading="lazy">
                    <span class="chatw__dot"></span>
                </span>
                <span class="chatw__meta">
                    <span class="chatw__name"><?= htmlspecialchars($__c['name']) ?></span>
                    <?php if (!empty($__c['role'])): ?><span class="chatw__role"><?= htmlspecialchars($__c['role']) ?></span><?php endif; ?>
                    <span class="chatw__status">Available</span>
                </span>
                <i class="fas fa-chevron-right chatw__chev"></i>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Step 2: a detail popup per counsellor (all their contacts) -->
    <?php foreach ($__consultants as $__i => $__c):
        $__wa = preg_replace('/[^0-9]/', '', (string) $__c['whatsapp']);
        $__tel = preg_replace('/[^0-9+]/', '', (string) $__c['phone']);
    ?>
    <div class="chatw__detail" id="chatDetail-<?= (int) $__i ?>" role="dialog" aria-label="<?= htmlspecialchars($__c['name']) ?>" aria-hidden="true">
        <div class="chatw__dhead">
            <button class="chatw__back" type="button" data-chat-back aria-label="Back"><i class="fas fa-arrow-left"></i></button>
            <span class="chatw__dtitle"><?= htmlspecialchars($__c['name']) ?></span>
            <button class="chatw__close" type="button" data-chat-close aria-label="Close">&times;</button>
        </div>
        <div class="chatw__dbody">
            <?php if (!empty($__c['qr_image'])): ?>
                <img src="<?= htmlspecialchars($__url($__c['qr_image']), ENT_QUOTES) ?>" alt="<?= htmlspecialchars($__c['name']) ?> QR code" class="chatw__qr">
                <p class="chatw__qrhint">Scan the QR code to connect</p>
            <?php else: ?>
                <img src="<?= htmlspecialchars($__url($__c['photo'] ?: 'images/logo.png'), ENT_QUOTES) ?>" alt="<?= htmlspecialchars($__c['name']) ?>" class="chatw__davatar">
            <?php endif; ?>
            <div class="chatw__dname"><?= htmlspecialchars($__c['name']) ?></div>
            <?php if (!empty($__c['role'])): ?><div class="chatw__drole"><?= htmlspecialchars($__c['role']) ?></div><?php endif; ?>

            <div class="chatw__links">
                <?php if ($__wa): ?>
                    <a class="chatw__link" href="https://wa.me/<?= htmlspecialchars($__wa, ENT_QUOTES) ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp"></i><span><?= htmlspecialchars($__c['whatsapp']) ?></span><i class="fas fa-chevron-right chatw__lchev"></i></a>
                <?php endif; ?>
                <?php if (!empty($__c['wechat'])): ?>
                    <div class="chatw__link chatw__link--static">
                        <i class="fab fa-weixin"></i><span>WeChat: <?= htmlspecialchars($__c['wechat']) ?></span></div>
                <?php endif; ?>
                <?php if ($__tel): ?>
                    <a class="chatw__link" href="tel:<?= htmlspecialchars($__tel, ENT_QUOTES) ?>">
                        <i class="fas fa-phone"></i><span><?= htmlspecialchars($__c['phone']) ?></span><i class="fas fa-chevron-right chatw__lchev"></i></a>
                <?php endif; ?>
                <?php if (!empty($__c['email'])): ?>
                    <a class="chatw__link" href="mailto:<?= htmlspecialchars($__c['email'], ENT_QUOTES) ?>">
                        <i class="fas fa-envelope"></i><span><?= htmlspecialchars($__c['email']) ?></span><i class="fas fa-chevron-right chatw__lchev"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <button class="chatw__launcher" id="chatLauncher" type="button" aria-label="Chat with our admission team">
        <i class="fas fa-comment-dots"></i> <span>Hi, how can I help?</span>
    </button>
</div>
<script>
(function () {
    var w = document.getElementById('chatWidget'),
        l = document.getElementById('chatLauncher');
    if (!w || !l) return;
    var details = w.querySelectorAll('.chatw__detail');

    function closeAll() { w.classList.remove('open', 'detailing'); details.forEach(function (d) { d.classList.remove('show'); d.setAttribute('aria-hidden', 'true'); }); }
    function openList() { w.classList.add('open'); w.classList.remove('detailing'); details.forEach(function (d) { d.classList.remove('show'); d.setAttribute('aria-hidden', 'true'); }); }
    function openDetail(id) {
        w.classList.add('open', 'detailing');
        details.forEach(function (d) {
            var on = d.id === 'chatDetail-' + id;
            d.classList.toggle('show', on);
            d.setAttribute('aria-hidden', on ? 'false' : 'true');
        });
    }

    l.addEventListener('click', function (e) { e.stopPropagation(); if (w.classList.contains('open')) closeAll(); else openList(); });
    w.querySelectorAll('.chatw__agent').forEach(function (a) {
        a.addEventListener('click', function (e) { e.stopPropagation(); openDetail(a.dataset.agent); });
    });
    w.querySelectorAll('[data-chat-back]').forEach(function (b) { b.addEventListener('click', function (e) { e.stopPropagation(); openList(); }); });
    w.querySelectorAll('[data-chat-close]').forEach(function (b) { b.addEventListener('click', function (e) { e.stopPropagation(); closeAll(); }); });
    document.addEventListener('click', function (e) { if (w.classList.contains('open') && !e.target.closest('#chatWidget')) closeAll(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeAll(); });
})();
</script>
<?php endif; ?>
