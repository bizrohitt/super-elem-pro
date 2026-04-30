<?php
namespace SuperElemPro\Modules\Forms\Admin;

if (!defined('ABSPATH'))
    exit;

/**
 * Form Submissions Admin Page
 * 
 * @since 1.0.0
 */
class SubmissionsPage
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_menu_page']);
    }

    public function add_menu_page()
    {
        add_submenu_page(
            'super-elem-pro',
            __('Form Submissions', 'super-elem-pro'),
            __('Submissions', 'super-elem-pro'),
            'manage_options',
            'sep-form-submissions',
            [$this, 'render_page']
        );
    }

    public function render_page()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sep_form_submissions';

        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            echo '<div class="wrap"><h1>' . esc_html__('Form Submissions', 'super-elem-pro') . '</h1><p>' . esc_html__('Database table not found. Please deactivate and reactivate the plugin.', 'super-elem-pro') . '</p></div>';
            return;
        }

        $submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 100");

        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Form Submissions', 'super-elem-pro'); ?></h1>
            <hr class="wp-header-end">
            
            <table class="wp-list-table widefat fixed striped table-view-list">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column column-primary"><?php esc_html_e('Form', 'super-elem-pro'); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e('Data', 'super-elem-pro'); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e('IP Address', 'super-elem-pro'); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e('Date', 'super-elem-pro'); ?></th>
                    </tr>
                </thead>
                <tbody id="the-list">
                    <?php if (!empty($submissions)): ?>
                        <?php foreach ($submissions as $sub): 
                            $fields = json_decode($sub->fields, true);
                        ?>
                            <tr>
                                <td class="column-primary" data-colname="Form">
                                    <strong><?php echo esc_html($sub->form_name); ?></strong>
                                    <div class="row-actions">
                                        <span class="id">ID: <?php echo esc_html($sub->id); ?></span>
                                    </div>
                                </td>
                                <td data-colname="Data">
                                    <?php if (!empty($fields) && is_array($fields)): ?>
                                        <ul style="margin:0;">
                                        <?php foreach ($fields as $field): ?>
                                            <li><strong><?php echo esc_html($field['label'] ?? $field['name']); ?>:</strong> <?php echo esc_html($field['value']); ?></li>
                                        <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <em><?php esc_html_e('No data', 'super-elem-pro'); ?></em>
                                    <?php endif; ?>
                                </td>
                                <td data-colname="IP Address">
                                    <?php echo esc_html($sub->user_ip); ?>
                                </td>
                                <td data-colname="Date">
                                    <?php echo esc_html(wp_date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($sub->created_at))); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr class="no-items">
                            <td class="colspanchange" colspan="4"><?php esc_html_e('No submissions found.', 'super-elem-pro'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}