@extends('layouts.site')

@section('content')

<!-- ===================== HERO ===================== -->
<section class="partner-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2" data-i18n="partner.eyebrow">Partner Program</span>
        <h1 data-i18n="partner.page_title">Why partner with Xinjiang Medical University</h1>
        <p data-i18n="partner.hero_sub">Join our global network of agencies and consultants recruiting international students for MBBS programs in China.</p>
    </div>
</section>

<div class="container">
    <nav class="partner-crumb" aria-label="breadcrumb">
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>Be Our Partner</span>
    </nav>
</div>

<main class="partner-page-wrap">
  <section class="partner-container">
    <div class="partner-split">
      <div class="partner-text">
        <p>
          Our Partner Program is designed for professional consultants and education organizations who want to support students in starting their medical education journey in China with confidence.
        </p>
        <p>
          By joining our partner network, you receive structured guidance, regular updates, and a responsive admissions support team. We focus on transparent communication, reliable documentation flow, and student-centered outcomes.
        </p>
        <p>
          Partners can work with us to explore suitable courses, evaluate applicant profiles, and streamline submissions through a practical collaboration framework aligned with Xinjiang Medical University standards.
        </p>
        <ul class="partner-highlights">
          <li data-i18n="partner.highlight1">Structured admissions guidance from inquiry to enrollment</li>
          <li data-i18n="partner.highlight2">Clear and transparent documentation workflow</li>
          <li data-i18n="partner.highlight3">Fast response support for consultants and institutions</li>
          <li data-i18n="partner.highlight4">Student-focused program recommendations</li>
        </ul>
      </div>
    </div>

    <div class="partner-split">
      <div class="partner-text">
        <h3 data-i18n="partner.program_heading">Our partner program</h3>
        <p>
          Our mission is to offer a reliable process that helps your team guide students with clarity and professionalism. We provide up-to-date admission requirements, timelines, and program details so your counseling team can operate with confidence.
        </p>
        <p>
          With consistent support and transparent procedures, your organization can improve conversion quality, reduce uncertainty, and deliver a better experience to every applicant.
        </p>
        <ul class="partner-highlights">
          <li data-i18n="partner.program_h1">Professional onboarding and collaboration setup</li>
          <li data-i18n="partner.program_h2">Regular updates on policy and application requirements</li>
          <li data-i18n="partner.program_h3">Practical communication channel with admissions support</li>
          <li data-i18n="partner.program_h4">Long-term partnership model for sustainable growth</li>
        </ul>
      </div>
    </div>

    <div class="partner-wave">
      <div>
        <h4 data-i18n="partner.wave_title">Still having questions about our partnership programs?</h4>
        <p data-i18n="partner.wave_desc">Have questions about the collaboration process? Reach out and our team will guide you through the details and onboarding steps.</p>
        <a class="btn" href="contact" target="_blank" rel="noopener noreferrer" data-i18n="partner.wave_btn">Contact Us Now</a>
      </div>
    </div>

    <div class="partner-form-wrap">
      <h3 data-i18n="partner.form_title">Register as recruitment partner with Xinjiang Medical University</h3>
      <p class="sub" data-i18n="partner.form_sub">Complete this form and submit once. Your registration details will be sent directly to our partnership desk at partners@xjmu.online for review.</p>

      <div id="partnerFormAlert" class="partner-alert"></div>

      <form id="partnerRegistrationForm" class="partner-form" action="send_partner_registration" method="post">
        <input type="text" name="first_name" class="form-control" placeholder="First Name*" data-i18n-placeholder="partner.first_name" required>
        <input type="text" name="last_name" class="form-control" placeholder="Last Name*" data-i18n-placeholder="partner.last_name" required>

        <input type="email" name="email" class="form-control" placeholder="Email*" data-i18n-placeholder="partner.email" required>
        <input type="text" name="mobile" class="form-control" placeholder="Mobile number*" data-i18n-placeholder="partner.mobile" required>

        <input type="text" name="company_name" class="form-control" placeholder="Company Name*" data-i18n-placeholder="partner.company_name" required>
        <input type="text" name="current_business" class="form-control" placeholder="Current Business*" data-i18n-placeholder="partner.current_business" required>

        <input type="text" name="address" class="form-control" placeholder="Address*" data-i18n-placeholder="partner.address" required>
        <input type="text" name="street_address" class="form-control" placeholder="Street Address*" data-i18n-placeholder="partner.street_address" required>

        <input type="text" name="city" class="form-control" placeholder="City*" data-i18n-placeholder="partner.city" required>
        <input type="text" name="state" class="form-control" placeholder="State*" data-i18n-placeholder="partner.state" required>

        <input type="text" name="pincode" class="form-control" placeholder="Pin Code/Zip Code*" data-i18n-placeholder="partner.pincode" required>
        <input type="text" name="region" class="form-control" placeholder="State/Province/Region*" data-i18n-placeholder="partner.region" required>

        <input type="text" name="whatsapp" class="form-control full" placeholder="Whatsapp Number*" data-i18n-placeholder="partner.whatsapp" required>

        <div class="partner-submit-wrap">
          <button type="submit" id="partnerSubmitBtn" class="partner-submit" data-i18n="partner.register_btn">Register</button>
        </div>
      </form>
    </div>
  </section>
</main>

<script src="js/be-our-partner.js?v=20260217a"></script>
@endsection
