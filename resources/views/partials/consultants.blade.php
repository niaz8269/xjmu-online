@if (count($siteConsultants['agents']))
<!-- Admission Consultants — WhatsApp-style chat widget (admin-managed) -->
<div class="chatw" id="chatWidget">

    <!-- Step 1: list of counsellors -->
    <div class="chatw__panel" id="chatPanel" role="dialog" aria-label="Admission counseling" aria-hidden="true">
        <div class="chatw__head">
            <button class="chatw__close" type="button" data-chat-close aria-label="Close">&times;</button>
            <p>{{ $siteConsultants['site_name'] }}'s admission counseling team is here to answer your queries!</p>
        </div>
        <div class="chatw__list">
            @foreach ($siteConsultants['agents'] as $i => $c)
            <button class="chatw__agent" type="button" data-agent="{{ $i }}">
                <span class="chatw__avatar">
                    <img src="{{ $c['photo_url'] }}" alt="{{ $c['name'] }}" loading="lazy">
                    <span class="chatw__dot"></span>
                </span>
                <span class="chatw__meta">
                    <span class="chatw__name">{{ $c['name'] }}</span>
                    @if ($c['role'])<span class="chatw__role">{{ $c['role'] }}</span>@endif
                    <span class="chatw__status">Available</span>
                </span>
                <i class="fas fa-chevron-right chatw__chev"></i>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Step 2: a detail popup per counsellor (all their contacts) -->
    @foreach ($siteConsultants['agents'] as $i => $c)
    <div class="chatw__detail" id="chatDetail-{{ $i }}" role="dialog" aria-label="{{ $c['name'] }}" aria-hidden="true">
        <div class="chatw__dhead">
            <button class="chatw__back" type="button" data-chat-back aria-label="Back"><i class="fas fa-arrow-left"></i></button>
            <span class="chatw__dtitle">{{ $c['name'] }}</span>
            <button class="chatw__close" type="button" data-chat-close aria-label="Close">&times;</button>
        </div>
        <div class="chatw__dbody">
            @if ($c['qr_url'])
                <img src="{{ $c['qr_url'] }}" alt="{{ $c['name'] }} QR code" class="chatw__qr">
                <p class="chatw__qrhint">Scan the QR code to connect</p>
            @else
                <img src="{{ $c['photo_url'] }}" alt="{{ $c['name'] }}" class="chatw__davatar">
            @endif
            <div class="chatw__dname">{{ $c['name'] }}</div>
            @if ($c['role'])<div class="chatw__drole">{{ $c['role'] }}</div>@endif

            <div class="chatw__links">
                @if ($c['wa_digits'])
                    <a class="chatw__link" href="https://wa.me/{{ $c['wa_digits'] }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp"></i><span>{{ $c['whatsapp'] }}</span><i class="fas fa-chevron-right chatw__lchev"></i></a>
                @endif
                @if ($c['wechat'])
                    <div class="chatw__link chatw__link--static">
                        <i class="fab fa-weixin"></i><span>WeChat: {{ $c['wechat'] }}</span></div>
                @endif
                @if ($c['tel'])
                    <a class="chatw__link" href="tel:{{ $c['tel'] }}">
                        <i class="fas fa-phone"></i><span>{{ $c['phone'] }}</span><i class="fas fa-chevron-right chatw__lchev"></i></a>
                @endif
                @if ($c['email'])
                    <a class="chatw__link" href="mailto:{{ $c['email'] }}">
                        <i class="fas fa-envelope"></i><span>{{ $c['email'] }}</span><i class="fas fa-chevron-right chatw__lchev"></i></a>
                @endif
            </div>
        </div>
    </div>
    @endforeach

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
@endif
