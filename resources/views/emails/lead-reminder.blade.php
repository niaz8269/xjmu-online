<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #16202e; line-height: 1.6;">
  <h2 style="color: #0b2a4a;">Your MBBS application at Xinjiang Medical University</h2>
  <p>Dear {{ $name }},</p>
  @if ($registered)
    <p>You created an account on our applicant portal, but your application has not been submitted yet. Your details are saved, and you can continue from where you stopped.</p>
  @else
    <p>Thank you for your interest in studying at Xinjiang Medical University. We wanted to check whether you still need help with your application.</p>
  @endif
  <p><strong>To complete your application you need</strong></p>
  <ul>
    <li>your passport and a photo with a white background</li>
    <li>your school certificates and transcripts</li>
    <li>a study plan, a physical examination record and a non-criminal record report</li>
  </ul>
  <p><a href="{{ $portalUrl }}" style="display: inline-block; background: #e62129; color: #ffffff; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: bold;">{{ $registered ? 'Continue my application' : 'Start my application' }}</a></p>
  <p>If you have a question about fees, intakes or documents, reply to this email and our admissions team will help you.</p>
  <p>If you are no longer interested, reply and tell us, and we will not write again.</p>
  <p>Kind regards,<br>International Students Admissions<br>Xinjiang Medical University</p>
</div>
