<?php
/**
 * Admin Bootstrap
 *
 * @package SuperElemPro\Admin
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Admin
 */
class Admin
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        $this->handle_actions();
    }

    /**
     * Register admin menu.
     *
     * @return void
     */
    public function register_menu(): void
    {

        // Main menu item
        add_menu_page(
            __('Super Elem Pro', 'super-elem-pro'),
            __('Super Elem Pro', 'super-elem-pro'),
            'manage_options',
            'super-elem-pro',
            [$this, 'render_settings_page'],
            'dashicons-superhero',
            58
        );

        // Settings submenu
        add_submenu_page(
            'super-elem-pro',
            __('Settings', 'super-elem-pro'),
            __('Settings', 'super-elem-pro'),
            'manage_options',
            'super-elem-pro',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register plugin settings with WordPress Settings API.
     *
     * @return void
     */
    public function register_settings(): void
    {

        register_setting(
            'sep_settings_group',
            'sep_modules_enabled',
            [
                'type' => 'array',
                'sanitize_callback' => [$this, 'sanitize_modules'],
                'default' => sep_get_default_modules(),
            ]
        );
    }

    /**
     * Sanitize module settings.
     *
     * @param mixed $input Raw input.
     * @return array Sanitized modules array.
     */
    public function sanitize_modules($input): array
    {

        $defaults = sep_get_default_modules();
        $sanitized = [];

        foreach ($defaults as $key => $default) {
            $sanitized[$key] = isset($input[$key]) && '1' === $input[$key];
        }

        return $sanitized;
    }

    /**
     * Render the main settings page.
     *
     * @return void
     */
    public function render_settings_page(): void
    {

        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'super-elem-pro'));
        }

        $settings_page = new Settings();
        $settings_page->render();
    }

    /**
     * Handle admin page actions (template toggle, etc.)
     *
     * @return void
     */
    private function handle_actions(): void
    {

        add_action('admin_init', function () {

            $action = sanitize_key($_GET['sep_action'] ?? '');

            if (!$action) {
                return;
            }

            switch ($action) {

                case 'toggle_template':
                    if (!check_admin_referer('sep_toggle_template')) {
                        break;
                    }
                    $template_id = absint($_GET['template_id'] ?? 0);
                    if ($template_id) {
                        $current = get_post_meta($template_id, '_sep_template_active', true);
                        update_post_meta($template_id, '_sep_template_active', $current ? '' : '1');
                    }
                    wp_safe_redirect(admin_url('admin.php?page=sep-theme-builder&updated=1'));
                    exit;

                case 'new_template':
                    if (!check_admin_referer('sep_new_template')) {
                        break;
                    }
                    $type = sanitize_key($_GET['template_type'] ?? 'header');
                    $id = wp_insert_post([
                        'post_title' => ucfirst($type) . ' Template',
                        'post_type' => \SuperElemPro\Modules\ThemeBuilder\ThemeBuilder::CPT_SLUG,
                        'post_status' => 'draft',
                    ]);
                    if ($id && !is_wp_error($id)) {
                        update_post_meta($id, '_sep_template_type', $type);
                        // Redirect to Elementor editor
                        wp_safe_redirect(add_query_arg([
                            'post' => $id,
                            'action' => 'elementor',
                        ], admin_url('post.php')));
                        exit;
                    }
                    break;
            }
        });
    }
}