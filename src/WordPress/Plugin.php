<?php

declare(strict_types=1);

namespace MortarDigital\Example\WordPress;

/**
 * WordPress entry point. The main plugin file, or an MU-plugin loader,
 * calls boot() once with this package's directory and public URL.
 */
final class Plugin
{
    private static ?self $instance = null;

    private function __construct(
        private readonly string $dir,
        private readonly string $url,
    ) {}

    public static function boot(string $dir, string $url): self
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        self::$instance = new self(rtrim($dir, '/'), rtrim($url, '/'));

        add_action('init', self::$instance->init(...));

        return self::$instance;
    }

    public static function instance(): ?self
    {
        return self::$instance;
    }

    public function init(): void
    {
        $this->loadMuPluginTranslations();
        $this->registerSetting();
        (new RestController())->register();
        $this->registerBlock();

        add_action('wp_enqueue_scripts', $this->enqueueFront(...));

        if (is_admin()) {
            (new AdminPage($this->url, $this->dir))->register();
        }
    }

    /**
     * Sanitises the settings option. An empty note is stored as an empty
     * array so an untouched install keeps the default.
     *
     * @return array{note?: string}
     */
    public function sanitizeOption(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $note = isset($value['note']) && is_string($value['note']) ? sanitize_text_field($value['note']) : '';

        return $note === '' ? [] : ['note' => $note];
    }

    /**
     * WordPress loads translations for regular plugins on its own. An
     * MU-plugin install has to load them itself.
     */
    private function loadMuPluginTranslations(): void
    {
        if (str_starts_with(wp_normalize_path($this->dir), wp_normalize_path(WPMU_PLUGIN_DIR))) {
            load_muplugin_textdomain('mortar-example', basename($this->dir) . '/languages');
        }
    }

    /** Sanitises writes to the settings option that bypass the settings screen. */
    private function registerSetting(): void
    {
        register_setting('mortar_example', 'mortar_example_settings', [
            'type' => 'object',
            'default' => [],
            'show_in_rest' => false,
            'sanitize_callback' => $this->sanitizeOption(...),
        ]);
    }

    /**
     * The editor script is registered by handle. A block.json "file:" path
     * resolves symlinks, which breaks the URL when the package is symlinked
     * into a site.
     */
    private function registerBlock(): void
    {
        wp_register_script(
            'mortar-example-notice-editor',
            $this->url . '/blocks/notice/index.js',
            ['wp-blocks', 'wp-block-editor', 'wp-element', 'wp-i18n'],
            $this->version('blocks/notice/index.js'),
            ['in_footer' => true],
        );
        wp_set_script_translations('mortar-example-notice-editor', 'mortar-example');

        register_block_type($this->dir . '/blocks/notice', [
            'render_callback' => static function (): string {
                return '<p ' . get_block_wrapper_attributes() . '>' . esc_html(mortar_example_message()) . '</p>';
            },
        ]);

        add_shortcode(
            'mortar_example',
            static fn(): string => '<p class="mortar-example">' . esc_html(mortar_example_message()) . '</p>',
        );
    }

    private function enqueueFront(): void
    {
        // Minified build (pnpm build) unless SCRIPT_DEBUG asks for the readable source.
        $runtime = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? 'assets/front.js' : 'assets/front.min.js';

        wp_enqueue_script('mortar-example', $this->url . '/' . $runtime, [], $this->version($runtime), [
            'strategy' => 'defer',
            'in_footer' => false,
        ]);
        wp_add_inline_script(
            'mortar-example',
            'window.mortarExample = ' . wp_json_encode(['message' => mortar_example_message()], JSON_HEX_TAG | JSON_HEX_AMP) . ';',
            'before',
        );
    }

    private function version(string $path): string
    {
        $mtime = @filemtime($this->dir . '/' . $path);

        return $mtime === false ? '0' : (string) $mtime;
    }
}
