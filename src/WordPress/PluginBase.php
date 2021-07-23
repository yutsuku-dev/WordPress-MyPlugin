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

    public function __construct(string $pluginRootFile)
    {
        $this->pluginRootFile = $pluginRootFile;
    }

    protected function build()
    {
        $this->fetcher = Fetcher\Factory::build();
        $this->usersController = new Api\UsersController($this->fetcher);
    }

    protected function enable()
    {
        register_activation_hook(__FILE__, static function () {
            add_rewrite_endpoint(Slug::NAME, EP_ROOT);
            flush_rewrite_rules();
        });
    }

    protected function disable()
    {
    }

    protected function populateMetadata()
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

    protected function addActions()
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
                ($this->metadata['Plugin Name'] ?? __NAMESPACE__),
                plugin_dir_url($this->pluginRootFile) . 'src/templates/js/main.js',
                [],
                ($this->metadata['Version'] ?? '1.0'),
                true
            );
        });

        add_action('rest_api_init', function () {
            $this->usersController->registerRoutes();
        });
    }

    protected function addFilters()
    {
        add_filter('query_vars', static function ($vars) {
            $vars[] = rawurlencode(Slug::NAME);
            return $vars;
        });

        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if ($handle === ($this->metadata['Plugin Name'] ?? __NAMESPACE__)) {
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

            add_action('wp_enqueue_scripts', function () {
                wp_enqueue_script(
                    ($this->metadata['Plugin Name'] ?? __NAMESPACE__),
                    plugin_dir_url($this->pluginRootFile) . 'src/templates/js/main.js',
                    [],
                    ($this->metadata['Version'] ?? '1.0'),
                    true
                );
                wp_enqueue_style(
                    ($this->metadata['Plugin Name'] ?? __NAMESPACE__),
                    plugin_dir_url($this->pluginRootFile) . 'src/templates/css/main.css',
                    [],
                    ($this->metadata['Version'] ?? '1.0')
                );
            });

            $template = locate_template('Yutsuku/WordPress/MyPlugin/index.php');

            if (!$template) {
                $template = dirname($this->pluginRootFile) . '/src/templates/index.php';
            }

            return $template;
        });
    }

    public function init()
    {
        $this->populateMetadata();
        $this->build();
        $this->enable();
        $this->addActions();
        $this->addFilters();
    }
}
