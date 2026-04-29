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
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_filter('manage_sep_form_submission_posts_columns', [$this, 'set_columns']);
        add_action('manage_sep_form_submission_posts_custom_column', [$this, 'render_column'], 10, 2);
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'sep_submission_details',
            __('Submission Details', 'super-elem-pro'),
            [$this, 'render_submission_details'],
            'sep_form_submission',
            'normal',
            'high'
        );
    }

    public function render_submission_details($post)
    {
        $form_data = get_post_meta($post->ID, '_sep_form_data', true);
        $user_ip = get_post_meta($post->ID, '_sep_user_ip', true);
        $user_agent = get_post_meta($post->ID, '_sep_user_agent', true);
        $submission_date = get_post_meta($post->ID, '_sep_submission_date', true);

        ?>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php esc_html_e('Field', 'super-elem-pro'); ?></th>
                    <th><?php esc_html_e('Value', 'super-elem-pro'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($form_data)): ?>
                    <?php foreach ($form_data as $field): ?>
                        <tr>
                            <td><strong><?php echo esc_html($field['label']); ?></strong></td>
                            <td><?php echo esc_html($field['value']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2"><?php esc_html_e('No data available', 'super-elem-pro'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3><?php esc_html_e('Submission Info', 'super-elem-pro'); ?></h3>
        <table class="widefat">
            <tbody>
                <tr>
                    <td><strong><?php esc_html_e('Date:', 'super-elem-pro'); ?></strong></td>
                    <td><?php echo esc_html($submission_date); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('IP Address:', 'super-elem-pro'); ?></strong></td>
                    <td><?php echo esc_html($user_ip); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('User Agent:', 'super-elem-pro'); ?></strong></td>
                    <td><?php echo esc_html($user_agent); ?></td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    public function set_columns($columns)
    {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = __('Submission', 'super-elem-pro');
        $new_columns['form_data'] = __('Data', 'super-elem-pro');
        $new_columns['date'] = __('Date', 'super-elem-pro');

        return $new_columns;
    }

    public function render_column($column, $post_id)
    {
        switch ($column) {
            case 'form_data':
                $form_data = get_post_meta($post_id, '_sep_form_data', true);
                if (!empty($form_data)) {
                    echo esc_html(count($form_data)) . ' ' . __('fields', 'super-elem-pro');
                }
                break;
        }
    }
}