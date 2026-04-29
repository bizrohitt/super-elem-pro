<?php
/**
 * Popup Builder Module
 *
 * @package SuperElemPro\Modules\PopupBuilder
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\PopupBuilder;

use Elementor\Plugin as ElementorPlugin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class PopupBuilder
 */
class PopupBuilder
{

    /**
     * Custom post type for popups.
     */
    const CPT_SLUG = 'sep_popup';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->register_post_type();
        $this->register_document();
        $this->setup_frontend();
        $this->register_ajax();
        $this->add_admin_menu();
    }

    /**
     * Register the popup custom post type.
     *
     * @return void
     */
    private function register_post_type(): void
    {
        add_action('init', function () {
            register_post_type(self::CPT_SLUG, [
                'label' => __('SEP Popups', 'super-elem-pro'),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => false,
                'exclude_from_search' => true,
                'capability_type' => 'post',
                'supports' => ['title', 'editor', 'elementor'],
                'rewrite' => false,
            ]);
        });
    }

    /**
     * Register the Elementor document type for popups.
     *
     * @return void
     */
    private function register_document(): void
    {
        add_action('elementor/documents/register', function ($documents_manager) {
            if (class_exists(Documents\Popup::class)) {
                $documents_manager->register_document_type('sep-popup', Documents\Popup::class);
            }
        });
    }

    /**
     * Set up frontend popup rendering and trigger system.
     *
     * @return void
     */
    private function setup_frontend(): void
    {

        add_action('wp_footer', [$this, 'render_popups']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_popup_assets']);
    }

    /**
     * Enqueue popup CSS and JS on the frontend.
     *
     * @return void
     */
    public function enqueue_popup_assets(): void
    {

        // Check if any popups should show on this page
        if (!$this->has_active_popups()) {
            return;
        }

        wp_enqueue_style(
            'sep-popup',
            SEP_ASSETS_URL . 'css/popup.css',
            [],
            SEP_VERSION
        );

        wp_enqueue_script(
            'sep-popup',
            SEP_ASSETS_URL . 'js/popup.js',
            ['jquery'],
            SEP_VERSION,
            true
        );

        wp_localize_script('sep-popup', 'sepPopupConfig', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sep_popup_nonce'),
            'popups' => $this->get_popup_configs(),
        ]);
    }

    /**
     * Render all active popups in the footer.
     *
     * @return void
     */
    public function render_popups(): void
    {

        $popups = $this->get_active_popups();

        if (empty($popups)) {
            return;
        }

        // Popup overlay container
        echo '<div id="sep-popup-overlay" class="sep-popup-overlay" style="display:none;" aria-hidden="true"></div>';

        foreach ($popups as $popup) {
            $popup_id = $popup->ID;
            $triggers = get_post_meta($popup_id, '_sep_popup_triggers', true);
            $settings = get_post_meta($popup_id, '_sep_popup_settings', true);

            $trigger_data = htmlspecialchars(
                wp_json_encode($triggers ?: []),
                ENT_QUOTES,
                'UTF-8'
            );

            $settings_data = htmlspecialchars(
                wp_json_encode($settings ?: []),
                ENT_QUOTES,
                'UTF-8'
            );

            echo '<div 
                id="sep-popup-' . esc_attr($popup_id) . '" 
                class="sep-popup" 
                role="dialog" 
                aria-modal="true"
                aria-label="' . esc_attr(get_the_title($popup_id)) . '"
                data-popup-id="' . esc_attr($popup_id) . '"
                data-triggers="' . $trigger_data . '"
                data-settings="' . $settings_data . '"
                style="display:none;"
            >';

            // Close button
            echo '<button class="sep-popup-close" aria-label="' . esc_attr__('Close popup', 'super-elem-pro') . '">&times;</button>';

            // Elementor content
            echo '<div class="sep-popup-content">';
            echo ElementorPlugin::$instance->frontend->get_builder_content_for_display($popup_id, true);
            echo '</div>';

            echo '</div>';
        }
    }

    /**
     * Get all popups that should be active on the current page.
     *
     * @return array Array of WP_Post objects.
     */
    private function get_active_popups(): array
    {

        $all_popups = get_posts([
            'post_type' => self::CPT_SLUG,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_sep_popup_active',
                    'value' => '1',
                ],
            ],
        ]);

        $active = [];

        foreach ($all_popups as $popup) {
            // Check display conditions
            $conditions = get_post_meta($popup->ID, '_sep_popup_conditions', true);
            if ($this->check_popup_conditions($conditions)) {
                $active[] = $popup;
            }
        }

        return $active;
    }

    /**
     * Check if popup conditions match current page.
     *
     * @param mixed $conditions Saved conditions.
     * @return bool
     */
    private function check_popup_conditions($conditions): bool
    {

        // No conditions = show everywhere
        if (empty($conditions) || !is_array($conditions)) {
            return true;
        }

        foreach ($conditions as $condition) {
            $type = $condition['type'] ?? 'all';

            switch ($type) {
                case 'all':
                    return true;
                case 'front_page':
                    if (is_front_page())
                        return true;
                    break;
                case 'singular':
                    if (is_singular($condition['post_type'] ?? ''))
                        return true;
                    break;
                case 'archive':
                    if (is_archive())
                        return true;
                    break;
                case 'logged_in':
                    if (is_user_logged_in())
                        return true;
                    break;
                case 'logged_out':
                    if (!is_user_logged_in())
                        return true;
                    break;
            }
        }

        return false;
    }

    /**
     * Get popup configuration data for JavaScript.
     *
     * @return array
     */
    private function get_popup_configs(): array
    {

        $popups = $this->get_active_popups();
        $configs = [];

        foreach ($popups as $popup) {
            $triggers = get_post_meta($popup->ID, '_sep_popup_triggers', true);
            $settings = get_post_meta($popup->ID, '_sep_popup_settings', true);

            $configs[] = [
                'id' => $popup->ID,
                'triggers' => $triggers ?: [],
                'settings' => $settings ?: [],
            ];
        }

        return $configs;
    }

    /**
     * Check if there are any active popups for conditional asset loading.
     *
     * @return bool
     */
    private function has_active_popups(): bool
    {
        return !empty($this->get_active_popups());
    }

    /**
     * Register AJAX handlers for popup interactions.
     *
     * @return void
     */
    private function register_ajax(): void
    {

        // Log popup view/interaction
        add_action('wp_ajax_sep_popup_interaction', [$this, 'handle_popup_interaction']);
        add_action('wp_ajax_nopriv_sep_popup_interaction', [$this, 'handle_popup_interaction']);
    }

    /**
     * Handle popup interaction AJAX call.
     *
     * @return void
     */
    public function handle_popup_interaction(): void
    {

        check_ajax_referer('sep_popup_nonce', 'nonce');

        $popup_id = intval($_POST['popup_id'] ?? 0);
        $action = sanitize_key($_POST['popup_action'] ?? '');

        if (!$popup_id) {
            wp_send_json_error(['message' => 'Invalid popup ID.']);
        }

        // Store in cookie to prevent reshowing
        if ('dismiss' === $action) {
            $frequency = get_post_meta($popup_id, '_sep_popup_frequency', true);
            // Cookie handling is done client-side for performance
        }

        wp_send_json_success(['popup_id' => $popup_id, 'action' => $action]);
    }

    /**
     * Add popup manager to admin menu.
     *
     * @return void
     */
    private function add_admin_menu(): void
    {
        add_action('admin_menu', function () {
            add_submenu_page(
                'super-elem-pro',
                __('Popup Builder', 'super-elem-pro'),
                __('Popup Builder', 'super-elem-pro'),
                'manage_options',
                'sep-popups',
                [$this, 'render_admin_page']
            );
        });
    }

    /**
     * Render popup admin page.
     *
     * @return void
     */
    public function render_admin_page(): void
    {

        $popups = get_posts([
            'post_type' => self::CPT_SLUG,
            'post_status' => ['publish', 'draft'],
            'posts_per_page' => -1,
        ]);

        ?>
        <div class="wrap sep-popups-page">
            <h1><?php esc_html_e('Popup Builder', 'super-elem-pro'); ?></h1>
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=' . self::CPT_SLUG)); ?>"
                class="button button-primary">
                <?php esc_html_e('Create New Popup', 'super-elem-pro'); ?>
            </a>
            <br /><br />

            <?php if (empty($popups)): ?>
                <p><?php esc_html_e('No popups created yet. Click "Create New Popup" to get started.', 'super-elem-pro'); ?></p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Name', 'super-elem-pro'); ?></th>
                            <th><?php esc_html_e('Status', 'super-elem-pro'); ?></th>
                            <th><?php esc_html_e('Active', 'super-elem-pro'); ?></th>
                            <th><?php esc_html_e('Actions', 'super-elem-pro'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($popups as $popup):
                            $is_active = get_post_meta($popup->ID, '_sep_popup_active', true);
                            $edit_url = get_edit_post_link($popup->ID);
                            ?>
                            <tr>
                                <td><strong><?php echo esc_html($popup->post_title); ?></strong></td>
                                <td><?php echo esc_html($popup->post_status); ?></td>
                                <td><?php echo $is_active ? '✅' : '❌'; ?></td>
                                <td>
                                    <a href="<?php echo esc_url($edit_url); ?>">
                                        <?php esc_html_e('Edit', 'super-elem-pro'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <?php
    }
}