<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #16202e; line-height: 1.6;">
  <h2 style="color: #0b2a4a;">Update on your application</h2>
  <p>Dear {{ $name }},</p>
  @if ($status === 'Approved')
    <p>We are pleased to tell you that your application to Xinjiang Medical University has been <strong>approved</strong>.</p>
    <p>The admissions office will contact you with the next steps for your admission documents and visa.</p>
  @elseif ($status === 'Rejected')
    <p>Thank you for applying to Xinjiang Medical University. After reviewing your application, we are not able to approve it at this time.</p>
  @else
    <p>The admissions office has added a message to your application.</p>
  @endif
  @if ($remark !== '')
    <p><strong>Message from the admissions office</strong></p>
    <p style="background: #f5f8fc; border-left: 4px solid #0b2a4a; padding: 10px 14px;">{{ $remark }}</p>
  @endif
  <p><a href="{{ $portalUrl }}" style="display: inline-block; background: #e62129; color: #ffffff; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: bold;">Open the applicant portal</a></p>
  <p>If you have a question, reply to this email or contact the admissions office.</p>
  <p>Kind regards,<br>International Students Admissions<br>Xinjiang Medical University</p>
</div>
