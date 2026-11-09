<?php

declare(strict_types=1);

namespace MortarDigital\Example\WordPress;

/**
 * Settings → Mortar Example. The screen is a small React app built from
 * admin/ into assets/admin/.
 */
final class AdminPage
{
    private const SLUG = 'mortar-example';

    private string $hook = '';

    public function __construct(
        private readonly string $url,
        private readonly string $dir,
    ) {}

    public function register(): void
    {
        add_action('admin_menu', function (): void {
            $this->hook = (string) add_options_page(
                __('Mortar Example', 'mortar-example'),
                __('Mortar Example', 'mortar-example'),
                RestController::capability(),
                self::SLUG,
                $this->render(...),
            );
        });

        add_action('admin_enqueue_scripts', $this->enqueue(...));

        // A Settings link on the plugin row, for a normal install and for the
        // MU-plugin loader. Both files are named mortar-example.php.
        add_filter('plugin_action_links', static function (array $actions, string $file): array {
            if (basename($file) !== 'mortar-example.php' || ! current_user_can(RestController::capability())) {
                return $actions;
            }

            return [
                'settings' => sprintf('<a href="%s">%s</a>', esc_url(admin_url('options-general.php?page=' . self::SLUG)), esc_html__('Settings', 'mortar-example')),
                ...$actions,
            ];
        }, 10, 2);
    }

    public function render(): void
    {
        ?>
<div class="wrap">
<h1><?php esc_html_e('Mortar Example', 'mortar-example'); ?></h1>
<div id="mortar-example-admin"><p><?php esc_html_e('Loading…', 'mortar-example'); ?></p></div>
<noscript><p><?php esc_html_e('This screen needs JavaScript.', 'mortar-example'); ?></p></noscript>
</div>
        <?php
    }

    public function enqueue(string $hook): void
    {
        if ($hook !== $this->hook || $this->hook === '') {
            return;
        }

        $version = static function (string $path): string {
            $mtime = @filemtime($path);

            return $mtime === false ? '0' : (string) $mtime;
        };

        [$dependencies, $scriptVersion] = $this->scriptAsset();
        wp_enqueue_script('mortar-example-admin', $this->url . '/assets/admin/index.js', $dependencies, $scriptVersion, ['in_footer' => true]);
        wp_set_script_translations('mortar-example-admin', 'mortar-example');
        wp_add_inline_script('mortar-example-admin', 'window.mortarExampleAdmin = ' . wp_json_encode([
            'message' => mortar_example_message(),
        ], JSON_HEX_TAG | JSON_HEX_AMP) . ';', 'before');
        wp_enqueue_style('mortar-example-admin', $this->url . '/assets/admin.css', ['wp-components'], $version($this->dir . '/assets/admin.css'));
    }

    /**
     * Dependencies and version of the settings app, from the file `pnpm build`
     * writes beside it (assets/admin/index.asset.php).
     *
     * @return array{list<non-empty-string>, string}
     */
    private function scriptAsset(): array
    {
        $file = $this->dir . '/assets/admin/index.asset.php';
        $asset = is_file($file) ? require $file : null;
        $handles = [];

        foreach (is_array($asset) && is_array($asset['dependencies'] ?? null) ? $asset['dependencies'] : [] as $handle) {
            if (is_string($handle) && $handle !== '') {
                $handles[] = $handle;
            }
        }

        return [$handles, is_array($asset) && is_string($asset['version'] ?? null) ? $asset['version'] : '0'];
    }
}
