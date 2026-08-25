# Email Deliverability Checklist (xjmu.online)

Your app can successfully submit email to SMTP, but mailbox providers (especially Gmail) can still reject, quarantine, or silently filter messages when sender authentication is missing.

## 1) Add SPF record (required)
- Type: TXT
- Host/Name: @
- Value (typical iPage style):
  v=spf1 a mx include:websitewelcome.com ~all

If your provider gives a different include domain, use that provider-issued value.

## 2) Add DKIM record (required)
- Generate DKIM keys in your mail provider panel.
- Add TXT record with selector provided by provider.
- Common example:
  - Host/Name: default._domainkey
  - Value: v=DKIM1; k=rsa; p=MIIBI... (long public key)

## 3) Add DMARC record (required)
- Type: TXT
- Host/Name: _dmarc
- Value (safe starting policy):
  v=DMARC1; p=none; rua=mailto:postmaster@xjmu.online; adkim=s; aspf=s

After validating reports for a few days, move to stricter policy:
- p=quarantine
- later p=reject

## 4) Align sender identity
- Use the same domain in:
  - SMTP username/from (already no-reply@xjmu.online)
  - SPF domain
  - DKIM signing domain
  - DMARC policy domain

## 5) Verify DNS after adding records
Run:
- nslookup -type=txt xjmu.online
- nslookup -type=txt _dmarc.xjmu.online
- nslookup -type=txt default._domainkey.xjmu.online

Expected:
- SPF TXT visible on root domain
- DMARC TXT visible on _dmarc
- DKIM TXT visible on selector._domainkey

## 6) App support already added
Code now supports optional DKIM env variables in .env:
- SMTP_DKIM_DOMAIN
- SMTP_DKIM_SELECTOR
- SMTP_DKIM_PRIVATE_KEY_PATH
- SMTP_DKIM_IDENTITY

And logs Message-ID to help trace delivery with your provider.
