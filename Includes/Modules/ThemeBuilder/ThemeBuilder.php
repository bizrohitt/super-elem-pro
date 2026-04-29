<?php
/**
 * Theme Builder Module
 *
 * Enables building custom headers, footers, single post templates,
 * archive templates, search results, and 404 pages using Elementor.
 *
 * @package SuperElemPro\Modules\ThemeBuilder
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\ThemeBuilder;

use Elementor\Core\Documents_Manager;
use Elementor\Plugin as ElementorPlugin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ThemeBuilder
 */
class ThemeBuilder
{

    /**
     * Custom post type slug for theme templates.
     */
    const CPT_SLUG = 'sep_template';

    /**
     * Template types.
     */
    const TYPES = [
        'header' => 'Header',
        'footer' => 'Footer',
        'single' => 'Single Post/Page',
        'archive' => 'Archive',
        'search' => 'Search Results',
        '404' => '404 Page',
    ];

    /**
     * Constructor — registers everything.
     */
    public function __construct()
    {
        $this->register_post_type();
        $this->register_documents();
        $this->register_theme_builder_widgets();
        $this->setup_template_display();
        $this->add_admin_menu();
    }

    /**
     * Register the custom post type for storing theme templates.
     *
     * @return void
     */
    private function register_post_type(): void
    {

        add_action('init', function () {

            register_post_type(self::CPT_SLUG, [
                'label' => __('SEP Templates', 'super-elem-pro'),
                'labels' => [
                    'name' => __('SEP Templates', 'super-elem-pro'),
                    'singular_name' => __('SEP Template', 'super-elem-pro'),
                    'add_new' => __('Add New Template', 'super-elem-pro'),
                    'add_new_item' => __('Add New Template', 'super-elem-pro'),
                    'edit_item' => __('Edit Template', 'super-elem-pro'),
                ],
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => false, // We add it to our own menu
                'show_in_nav_menus' => false,
                'exclude_from_search' => true,
                'capability_type' => 'post',
                'supports' => ['title', 'editor', 'elementor'],
                'rewrite' => false,
            ]);

            // Register taxonomy for template type
            register_taxonomy('sep_template_type', self::CPT_SLUG, [
                'label' => __('Template Type', 'super-elem-pro'),
                'public' => false,
                'show_ui' => false,
                'show_in_nav_menus' => false,
                'hierarchical' => false,
                'rewrite' => false,
            ]);
        });
    }

    /**
     * Register Elementor document types for each template type.
     *
     * @return void
     */
    private function register_documents(): void
    {

        add_action('elementor/documents/register', function (Documents_Manager $documents_manager) {

            $documents_manager->register_document_type(
                'sep-header',
                Documents\Header::class
            );

            $documents_manager->register_document_type(
                'sep-footer',
                Documents\Footer::class
            );

            $documents_manager->register_document_type(
                'sep-single',
                Documents\Single::class
            );

            $documents_manager->register_document_type(
                'sep-archive',
                Documents\Archive::class
            );

            $documents_manager->register_document_type(
                'sep-search',
                Documents\Search::class
            );

            $documents_manager->register_document_type(
                'sep-404',
                Documents\Error404::class
            );
        });
    }

    /**
     * Register theme builder specific widgets.
     *
     * @return void
     */
    private function register_theme_builder_widgets(): void
    {

        add_action('elementor/widgets/register', function ($widgets_manager) {

            $widgets = [
                Widgets\SiteTitle::class,
                Widgets\SiteLogo::class,
                Widgets\NavMenu::class,
                Widgets\PostTitle::class,
                Widgets\PostContent::class,
                Widgets\PostFeaturedImage::class,
                Widgets\PostExcerpt::class,
                Widgets\PostInfo::class,
                Widgets\PostNavigation::class,
                Widgets\Breadcrumbs::class,
                Widgets\ArchiveTitle::class,
            ];

            foreach ($widgets as $widget_class) {
                if (class_exists($widget_class)) {
                    $widgets_manager->register(new $widget_class());
                }
            }
        });
    }

    /**
     * Set up template display on the frontend.
     * This is what actually shows your header/footer/etc. on pages.
     *
     * @return void
     */
    private function setup_template_display(): void
    {

        // Hook into theme to inject header template
        add_action('get_header', [$this, 'override_header']);

        // Hook into theme to inject footer template
        add_action('get_footer', [$this, 'override_footer']);

        // Filter page template to use our single/archive/search/404 templates
        add_filter('template_include', [$this, 'template_include'], 99);

        // Add body classes for our templates
        add_filter('body_class', [$this, 'body_class']);
    }

    /**
     * Override the theme header with our Elementor header template.
     *
     * @return void
     */
    public function override_header(): void
    {

        $header_id = $this->get_active_template('header');

        if (!$header_id) {
            return;
        }

        // Stop theme from loading its header
        remove_action('get_header', [$this, 'override_header']);

        // Output our Elementor header
        echo '<header class="sep-header" role="banner">';
        echo ElementorPlugin::$instance->frontend->get_builder_content_for_display($header_id, true);
        echo '</header>';

        // Load our fallback wrapper to close the page structure
        include SEP_TEMPLATES_DIR . 'theme-builder/header.php';

        // Stop WordPress from loading anything else for the header
        ob_start();
        add_action('get_footer', function () {
            ob_end_clean();
        });
    }

    /**
     * Override the theme footer with our Elementor footer template.
     *
     * @return void
     */
    public function override_footer(): void
    {

        $footer_id = $this->get_active_template('footer');

        if (!$footer_id) {
            return;
        }

        echo '<footer class="sep-footer" role="contentinfo">';
        echo ElementorPlugin::$instance->frontend->get_builder_content_for_display($footer_id, true);
        echo '</footer>';
    }

    /**
     * Override the page template for single, archive, search, and 404.
     *
     * @param string $template Current template file path.
     * @return string Modified template file path.
     */
    public function template_include(string $template): string
    {

        $type = $this->get_current_page_type();

        if (!$type) {
            return $template;
        }

        $template_id = $this->get_active_template($type);

        if (!$template_id) {
            return $template;
        }

        // Store the template ID globally for use in the template file
        global $sep_current_template_id;
        $sep_current_template_id = $template_id;

        // Return our fallback template which renders the Elementor content
        $fallback = SEP_TEMPLATES_DIR . 'theme-builder/fallback.php';

        return file_exists($fallback) ? $fallback : $template;
    }

    /**
     * Determine what type of page we're on.
     *
     * @return string|false Page type slug or false.
     */
    private function get_current_page_type()
    {

        if (is_singular()) {
            return 'single';
        }

        if (is_archive() || is_home()) {
            return 'archive';
        }

        if (is_search()) {
            return 'search';
        }

        if (is_404()) {
            return '404';
        }

        return false;
    }

    /**
     * Get the active template ID for a given template type.
     * Checks conditions to find the most specific matching template.
     *
     * @param string $type Template type (header, footer, single, etc.)
     * @return int|false Post ID or false if no template found.
     */
    public function get_active_template(string $type)
    {

        // Query all templates of this type
        $templates = get_posts([
            'post_type' => self::CPT_SLUG,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_sep_template_type',
                    'value' => $type,
                ],
                [
                    'key' => '_sep_template_active',
                    'value' => '1',
                ],
            ],
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);

        if (empty($templates)) {
            return false;
        }

        // For now return first matching template
        // (condition matching logic is handled in ConditionManager)
        $condition_manager = new Conditions\ConditionManager();

        foreach ($templates as $template) {
            if ($condition_manager->check($template->ID)) {
                return $template->ID;
            }
        }

        return false;
    }

    /**
     * Add body classes when our templates are active.
     *
     * @param array $classes Existing body classes.
     * @return array Modified body classes.
     */
    public function body_class(array $classes): array
    {

        if ($this->get_active_template('header')) {
            $classes[] = 'sep-custom-header';
        }

        if ($this->get_active_template('footer')) {
            $classes[] = 'sep-custom-footer';
        }

        return $classes;
    }

    /**
     * Add Theme Builder to the admin menu.
     *
     * @return void
     */
    private function add_admin_menu(): void
    {

        add_action('admin_menu', function () {

            add_submenu_page(
                'super-elem-pro',
                __('Theme Builder', 'super-elem-pro'),
                __('Theme Builder', 'super-elem-pro'),
                'manage_options',
                'sep-theme-builder',
                [$this, 'render_theme_builder_page']
            );
        });
    }

    /**
     * Render the Theme Builder admin page.
     *
     * @return void
     */
    public function render_theme_builder_page(): void
    {
        ?>
        <div class="wrap sep-theme-builder-page">
            <h1><?php echo esc_html__('Theme Builder', 'super-elem-pro'); ?></h1>
            <p><?php echo esc_html__('Create and manage your custom header, footer, and page templates below.', 'super-elem-pro'); ?>
            </p>

            <?php foreach (self::TYPES as $type => $label): ?>
                <div class="sep-template-type-section">
                    <h2><?php echo esc_html($label); ?></h2>
                    <?php $this->render_templates_for_type($type); ?>
                    <a href="<?php echo esc_url($this->get_new_template_url($type)); ?>" class="button button-primary">
                        <?php
                        /* translators: %s: Template type label */
                        printf(esc_html__('Add New %s Template', 'super-elem-pro'), esc_html($label));
                        ?>
                    </a>
                </div>
                <hr />
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Render list of templates for a specific type.
     *
     * @param string $type Template type.
     * @return void
     */
    private function render_templates_for_type(string $type): void
    {

        $templates = get_posts([
            'post_type' => self::CPT_SLUG,
            'post_status' => ['publish', 'draft'],
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_sep_template_type',
                    'value' => $type,
                ],
            ],
        ]);

        if (empty($templates)) {
            echo '<p>' . esc_html__('No templates created yet.', 'super-elem-pro') . '</p>';
            return;
        }

        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>' . esc_html__('Name', 'super-elem-pro') . '</th>';
        echo '<th>' . esc_html__('Status', 'super-elem-pro') . '</th>';
        echo '<th>' . esc_html__('Active', 'super-elem-pro') . '</th>';
        echo '<th>' . esc_html__('Actions', 'super-elem-pro') . '</th>';
        echo '</tr></thead><tbody>';

        foreach ($templates as $template) {
            $is_active = get_post_meta($template->ID, '_sep_template_active', true);
            $edit_url = get_edit_post_link($template->ID);
            $toggle_url = wp_nonce_url(
                add_query_arg([
                    'sep_action' => 'toggle_template',
                    'template_id' => $template->ID,
                ], admin_url('admin.php?page=sep-theme-builder')),
                'sep_toggle_template'
            );

            echo '<tr>';
            echo '<td><strong>' . esc_html($template->post_title) . '</strong></td>';
            echo '<td>' . esc_html($template->post_status) . '</td>';
            echo '<td>' . ($is_active ? '✅ Active' : '❌ Inactive') . '</td>';
            echo '<td>';
            echo '<a href="' . esc_url($edit_url) . '">' . esc_html__('Edit in Elementor', 'super-elem-pro') . '</a> | ';
            echo '<a href="' . esc_url($toggle_url) . '">';
            echo $is_active ? esc_html__('Deactivate', 'super-elem-pro') : esc_html__('Activate', 'super-elem-pro');
            echo '</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }

    /**
     * Get URL to create a new template of a specific type.
     *
     * @param string $type Template type.
     * @return string Admin URL.
     */
    private function get_new_template_url(string $type): string
    {
        return wp_nonce_url(
            add_query_arg([
                'sep_action' => 'new_template',
                'template_type' => $type,
            ], admin_url('admin.php?page=sep-theme-builder')),
            'sep_new_template'
        );
    }
}