# WordPress plugin template

This is the starting point for a Mortar Digital WordPress plugin. The code here is a working plugin called **Mortar Example**, so the tooling, the layout and the names are real and the tests pass. A new plugin is a copy of this repository with those stand-in names replaced.

Do that work in a new repository. This repository stays the template.

## Create the repository

1. On GitHub, open [Mortar-Digital/wordpress-plugin-template](https://github.com/Mortar-Digital/wordpress-plugin-template) and choose **Use this template**, then **Create a new repository**.
2. Set the owner to **Mortar-Digital**. Name the repository the product slug: the same word you will pass to `bin/rename`. Mortar Consent lives in `consent`, so a plugin called Mortar Widgets lives in `widgets`. The WordPress plugin slug (`mortar-widgets`) is added for you.
3. Clone the new repository.

From the command line, in an empty directory:

```sh
gh repo create Mortar-Digital/widgets --public --template Mortar-Digital/wordpress-plugin-template --clone
cd widgets
```

Use `--private` while the plugin is still internal. A plugin submitted to WordPress.org needs a public source repository.

## Rename the stand-in

From the new clone:

```sh
bin/rename widgets "Mortar Widgets"
```

`widgets` is the product slug. `"Mortar Widgets"` is the name WordPress shows to people. Add `--repo other-name` only when the GitHub repository is not named after the product slug. The script points CI, issues and security links at `Mortar-Digital/<repo>`, so the repository name and that flag have to match the repo you created.

The product slug is lowercase words separated by single hyphens (`consent`, `cookie-banner`). `example` is reserved for this template. Hyphens become capitals in the PHP namespace (`cookie-banner` becomes `MortarDigital\CookieBanner`) and underscores in function names (`mortar_cookie_banner_…`).

The script runs once. Once `mortar-example.php` has been renamed, running it again stops. It refreshes `composer.lock` and `pnpm-lock.yaml` before it exits.

### What the script replaces

| Stand-in | Becomes | Where it shows up |
| --- | --- | --- |
| `mortar-example` | `mortar-<product>` | Plugin file and directory, text domain, script handles, block name, wp-env mapping, Composer installer name, REST namespace |
| `mortar_example` | `mortar_<product>` | Functions, hooks, options, the shortcode |
| `mortarExample` | `mortar<Studly>` | `window.mortarExample` and `window.mortarExampleAdmin` |
| `MORTAR_EXAMPLE` | `MORTAR_<PRODUCT>` | The uninstall constant in `wp-config.php` |
| `MortarDigital\Example` | `MortarDigital\<Studly>` | PHP namespace, Composer autoload, PHPCS prefix |
| `Mortar Example` | the name you passed | Plugin header, headings, the sample message |
| `mortar-digital/example` | `mortar-digital/<product>` | Composer package name |
| `mortar-digital-example` | `mortar-digital-<product>` | `package.json` name |
| `https://mortar.digital/plugins/example/` | `https://mortar.digital/plugins/<product>/` | Plugin URI, homepage, docs link |
| `github.com/Mortar-Digital/wordpress-plugin-template` | `github.com/Mortar-Digital/<repo>` | CI badge, issues, source, security link |

Afterwards, search the tree for `example`. Prose such as "for example" is left as it is, because the script only replaces the tokens in this table. Anything else that still says example needs a decision.

### What you still edit

The script prints this list when it finishes.

- **Description.** The plugin header in the main PHP file, the `description` in `composer.json`, the opening paragraph of the plugin README (the file that remains after this guide is removed), and the short description plus the Description section in `readme.txt`.
- **readme.txt tags.** Replace `mortar` with this plugin's WordPress.org tags. Five at most.
- **SECURITY.md.** Replace the scope list with the attacks that matter for this plugin.
- **CONTRIBUTING.md.** Add product rules when this plugin has any. The prefix and text domain are already rewritten.
- **Artwork.** Edit `.wordpress-org/src/icon.html` and `banner.html`, then run `bin/render-wporg-assets` after `pnpm exec playwright install chromium`. Upload the PNGs with the WordPress.org assets. They are not part of the plugin zip.
- **Docs URL.** Change it when the docs will live somewhere other than `https://mortar.digital/plugins/<product>/`.
- **Pieces this plugin does not use.** `admin/` is the settings screen, `blocks/` is the dynamic block, and `assets/front.js` is the visitor script. Delete any of them that this plugin has no use for, and delete the matching `pnpm build` script and CI step. They are here so the pattern is already wired: a committed admin build, a committed minified runtime, and a block registered by script handle (a `file:` path in `block.json` breaks when the package is symlinked).

`bin/rename` deletes this guide from `README.md`. Everything under the marker becomes the plugin README.

## Check the result

Requires PHP 8.2+, Composer, Node 24+ and pnpm. The integration and browser tests also need Docker.

```sh
composer install && pnpm install
composer check
pnpm build
```

Commit what `bin/rename` changed, including both lockfiles and the renamed plugin file, and push it to the new repository.

Changes to the shared tooling belong in this template. Copy them into a plugin when that plugin should pick them up. Plugins do not track this repository as a remote.

<!-- template:setup:end -->

# Mortar Example

[![CI](https://github.com/Mortar-Digital/wordpress-plugin-template/actions/workflows/ci.yml/badge.svg)](https://github.com/Mortar-Digital/wordpress-plugin-template/actions/workflows/ci.yml)

Mortar Example is a starting point. Replace this description.

Made by [Mortar Digital](https://mortar.digital/). This is the development repository. [readme.txt](readme.txt) is the WordPress.org description.

## Installation

**From WordPress.org:** Plugins → Add New, then Settings → Mortar Example.

**From a release zip:** download `mortar-example-<version>.zip` from the [releases](https://github.com/Mortar-Digital/wordpress-plugin-template/releases) and upload it under Plugins → Add New → Upload Plugin.

**As a must-use plugin**, for example in a Bedrock site that tracks it as a Git submodule or Composer path package, place the repository at `mu-plugins/mortar-example/` and add a loader, `mu-plugins/mortar-example.php`:

```php
<?php
/**
 * Plugin Name: Mortar Example
 */

use MortarDigital\Example\WordPress\Plugin;

// Composer's autoloader provides the classes. Pass the MU paths explicitly,
// because a symlinked package would otherwise resolve to its real location.
if (class_exists(Plugin::class)) {
    Plugin::boot(WPMU_PLUGIN_DIR . '/mortar-example', WPMU_PLUGIN_URL . '/mortar-example');
    require_once WPMU_PLUGIN_DIR . '/mortar-example/functions.php';
}
```

Without Composer, `require WPMU_PLUGIN_DIR . '/mortar-example/mortar-example.php';` is enough: the main file has its own autoloader. If the plugin is loaded both ways, the regular copy stays inactive and shows a notice.

Only `assets/` and `blocks/` need to be reachable over HTTP. The rest of the plugin directory can be denied at the web server.

## Layout

| Path | Role |
| --- | --- |
| `mortar-example.php` | Plugin headers, autoloader, boots once |
| `functions.php` | Public functions. `mortar_example_message()` is the sample |
| `uninstall.php` | Deletes `mortar_example_settings` only when `MORTAR_EXAMPLE_DELETE_DATA_ON_UNINSTALL` is true |
| `src/` | Framework-agnostic classes. Unit tests run these without WordPress |
| `src/WordPress/` | Admin screen, REST route, block and assets. Integration tests run these inside WordPress |
| `admin/` | Settings screen source. `pnpm build` writes `assets/admin/` |
| `assets/front.js` | Front-end source. The committed `assets/front.min.js` is what visitors load |
| `blocks/notice/` | Dynamic block `mortar-example/notice`, plus the `[mortar_example]` shortcode |

The settings screen reads `window.mortarExampleAdmin.message`. An inline script sets `window.mortarExample.message` before `assets/front.min.js` runs. The block and the shortcode print the same text as `mortar_example_message()`.

When you add a daily cron event, clear it on deactivation and again in `uninstall.php`:

```php
register_deactivation_hook(__FILE__, static function (): void {
    wp_clear_scheduled_hook('mortar_example_daily');
});
```

## Hooks

| Filter | Receives | Use |
| --- | --- | --- |
| `mortar_example_capability` | `'manage_options'` | Capability for the settings screen and REST routes |

`GET /wp-json/mortar-example/v1/message` returns `{ "message": "..." }` to users with that capability.

## Development

Requires PHP 8.2+, Composer, Node 24+ and pnpm. The integration and browser tests also need Docker.

```sh
composer install && pnpm install
composer check         # syntax lint, ESLint, formatting, PHPCS, PHPStan, PHPUnit and the JS build test
composer format        # format PHP (PHP CS Fixer) and JS, JSON, CSS, YAML (Prettier)
pnpm build             # after editing assets/front.js or admin/; commit front.min.js and assets/admin/
pnpm start:admin       # rebuild the settings app on save while you work on it
bin/build-plugin       # build/mortar-example-<version>.zip
```

`composer check` runs:

- **Formatting checks:** PHP CS Fixer (PER-CS plus blank lines before control statements, trailing commas and ordered imports) and Prettier.
- **PHPCS** with the WordPress security, database, i18n and prefix sniffs. Formatting is PHP CS Fixer's job.
- **PHPStan** at level 9 with WordPress stubs.
- **PHPUnit** for the framework-agnostic classes in `src/`.
- **A Node test** that checks `front.min.js` matches a fresh build of `assets/front.js`.

### Integration tests

`tests/Integration` loads the real WordPress in wp-env, with the plugin active.

```sh
pnpm exec wp-env start
composer test:integration
```

`composer test:integration` runs PHPUnit inside the wp-env container.

### Browser tests

```sh
pnpm exec playwright install chromium   # once
pnpm exec wp-env start
pnpm test:e2e
```

The smoke test activates the plugin and checks that the front page loads its script. To run it against a local DDEV site that loads the plugin as an MU-plugin:

```sh
MORTAR_EXAMPLE_E2E_ENV=ddev MORTAR_EXAMPLE_E2E_SITE=../path/to/site pnpm test:e2e
```

CI runs the unit tests on PHP 8.2–8.5, and the integration and browser tests on WordPress 6.6 and the latest release.

## Uninstall

Deleting the plugin keeps `mortar_example_settings` unless `MORTAR_EXAMPLE_DELETE_DATA_ON_UNINSTALL` is defined as `true` in `wp-config.php`. On multisite that applies to every site.

## Contributing and security

See [CONTRIBUTING.md](CONTRIBUTING.md). Report security issues privately, as [SECURITY.md](SECURITY.md) describes.

## Licence

GPL-2.0-or-later. See [LICENSE](LICENSE). © Mortar Digital.
