<?php

declare(strict_types=1);

namespace Yutsuku\WordPress;

abstract class PluginBase
{
    protected Fetcher\FetcherInterface $fetcher;
    protected Api\UsersController $usersController;
    protected string $pluginRootFile;
    /** @see https://developer.wordpress.org/reference/functions/get_plugin_data/ */
    protected array $metadata;
    /** where from user-provided files will be loaded, if any. Defaults to `Yutsuku/WordPress/MyPlugin` */
    protected string $userTemplateBasedir = 'Yutsuku/WordPress/MyPlugin';

    public function __construct(string $pluginRootFile)
    {
        $this->pluginRootFile = $pluginRootFile;
    }

    public function build(): void
    {
        $this->fetcher = Fetcher\Factory::build();
        $this->usersController = new Api\UsersController($this->fetcher);
    }

    public function enable(): void
    {
        register_activation_hook(__FILE__, static function () {
            add_rewrite_endpoint(Slug::NAME, EP_ROOT);
            flush_rewrite_rules();
        });
    }

    public function disable(): void
    {
    }

    public function populateMetadata(): void
    {
        $this->metadata = get_file_data($this->pluginRootFile, [
            'Plugin Name' => 'Plugin Name',
            'Plugin URI' => 'Plugin URI',
            'Description' => 'Description',
            'Requires at least' => 'Requires at least',
            'Requires PHP' => 'Requires PHP',
            'Author' => 'Author',
            'Version' => 'Version',
            'Author URI' => 'Author URI',
            'License' => 'License',
            'License URI' => 'License URI',
            'Text Domain' => 'Text Domain',
            'Domain Path' => 'Domain Path',
            'Network' => 'Network',
            'Update URI' => 'Update URI',
        ]);
    }

    public function metadataHandle(): string
    {
        return $this->metadata['Plugin Name'] ?? __NAMESPACE__;
    }

    public function metadataVersion(): string
    {
        return $this->metadata['Version'] ?? '1.0';
    }

    public function resourceJsPath(): string
    {
        $file = get_template_directory() .
            $this->userTemplateBasedir .
            '/js/main.js';

        set_error_handler(function () {
        });

        if (file_exists($file)) {
            restore_error_handler();

            return $file;
        }

        restore_error_handler();

        return plugin_dir_url($this->pluginRootFile) .
            'src/templates/js/main.js';
    }

    public function resourceCssPath(): string
    {
        $file = get_template_directory() .
            $this->userTemplateBasedir .
            '/css/main.css';

        set_error_handler(function () {
        });

        if (file_exists($file)) {
            restore_error_handler();

            return $file;
        }

        restore_error_handler();

        return plugin_dir_url($this->pluginRootFile) .
            'src/templates/css/main.css';
    }

    public function resourceTemplatePath(): string
    {
        $template = locate_template($this->userTemplateBasedir . '/index.php');

        if (!$template) {
            $template = dirname($this->pluginRootFile) . '/src/templates/index.php';
        }

        return $template;
    }

    public function enqueueResources(): void
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_script(
                $this->metadataHandle(),
                $this->resourceJsPath(),
                [],
                $this->metadataVersion(),
                true
            );
            wp_enqueue_style(
                $this->metadataHandle(),
                $this->resourceCssPath(),
                [],
                $this->metadataVersion()
            );
        });

    }

    public function addActions(): void
    {
        add_action('init', function () {
            add_rewrite_rule(
                Slug::NAME .
                '/?([0-9]+)?/?$',
                'index.php?' .
                rawurlencode(Slug::NAME) .
                '=1&id=$matches[1]',
                'top'
            );
            flush_rewrite_rules();

            wp_register_script(
                $this->metadataHandle(),
                $this->resourceJsPath(),
                [],
                $this->metadataVersion(),
                true
            );
        });

        add_action('rest_api_init', function () {
            $this->usersController->registerRoutes();
        });
    }

    public function addFilters(): void
    {
        add_filter('query_vars', static function ($vars) {
            $vars[] = rawurlencode(Slug::NAME);
            return $vars;
        });

        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if ($handle === $this->metadataHandle()) {
                // Currently there is no other way to add attribute to script tag
                // which is required when loading ECMAScript modules
                // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
                return '<script type="module" src="' . esc_url($src) . '"></script>';
            }

            return $tag;
        }, 10, 3);

        add_filter('template_include', function ($template) {
            if (get_query_var(rawurlencode(Slug::NAME), false) === false) {
                return $template;
            }

            $this->enqueueResources();

            return $this->resourceTemplatePath();
        });
    }

    public function init(): void
    {
        $this->populateMetadata();
        $this->build();
        $this->enable();
        $this->addActions();
        $this->addFilters();
    }
}
