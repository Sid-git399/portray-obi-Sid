# Security Checklist

- Keep .env outside Git.
- Use prepared SQL statements.
- Hash passwords with password_hash().
- Verify passwords with password_verify().
- Protect POST forms with CSRF tokens.
- Regenerate sessions after authentication.
- Authorize administrative actions server-side.
- Never expose OBI credentials to clients.
