# Contributing

Thanks for helping. Bug reports, fixes and translations are all welcome.

- **Bugs and ideas:** open an issue with your WordPress and PHP versions, the theme and other plugins involved, and what you expected to happen.
- **Security problems:** do not open an issue. Follow [SECURITY.md](SECURITY.md).
- **Translations:** use [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/mortar-example/) once the plugin is listed there.
- **Support questions** from site owners belong in the plugin's WordPress.org support forum.

## Pull requests

1. Set up the tools described under **Development** in the [README](README.md).
2. Keep changes focused, and add or update tests for behaviour you change.
3. Run `composer check` and `composer format`. If you changed WordPress-facing code (`src/WordPress/`), also run `composer test:integration`. If you changed anything a visitor or an administrator sees, run the browser test too.
4. If you edited `assets/front.js` or the settings app in `admin/`, run `pnpm build` and commit the built `assets/front.min.js` or `assets/admin/` with it. CI fails if they are out of date.

CI runs the same checks on every pull request.

### Ground rules

- **No outside calls.** The plugin must never contact a third party or send telemetry.
- **Translatable and escaped.** Every visible string goes through the `mortar-example` text domain, and every output is escaped.
- **Prefixed.** Functions, hooks, options and script handles start with `mortar_example` or `mortar-example`. Classes live in `MortarDigital\Example`.

## Releasing

Maintainers only.

1. Set the new version in the `mortar-example.php` header and as `Stable tag` in `readme.txt`. Add the release to the readme changelog and to `CHANGELOG.md`.
2. Check `Tested up to` in `readme.txt` against the WordPress version the browser tests ran on.
3. Run `bin/build-plugin`. It fails if the readme and header versions disagree or the minified runtime is stale.
4. Install the zip on a clean site and run the official [Plugin Check](https://wordpress.org/plugins/plugin-check/) against it.
5. Tag the release (`vX.Y.Z`) and push the tag. If you schedule a cron event, clear it on deactivation and in `uninstall.php`.
