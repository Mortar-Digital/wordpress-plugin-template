# Security policy

## Reporting a vulnerability

Please report security problems privately through GitHub: open the repository's **Security** tab and choose **Report a vulnerability**. Do not open a public issue or support forum topic.

Include the plugin version, the steps to reproduce, and what an attacker could achieve. We will acknowledge your report, keep you updated, and agree a disclosure date with you once a fix is ready.

## Supported versions

Security fixes go into the latest release only. Please update before reporting.

## Scope

Of particular interest:

- Cross-site scripting through the settings screen, the notice block, the shortcode or the message shown in the admin app.
- Bypassing the capability check on the settings screen or `GET /wp-json/mortar-example/v1/message`.

Replace this list with the risks that matter for the real plugin.
