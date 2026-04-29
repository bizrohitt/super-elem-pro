<?php
namespace SuperElemPro\Modules\Forms\Fields;

if (!defined('ABSPATH'))
    exit;

/**
 * File Upload Field
 * 
 * @since 1.0.0
 */
class FileField
{

    public static function render($field, $index)
    {
        $field_id = 'field-' . $index;
        $field_name = !empty($field['field_name']) ? $field['field_name'] : 'field_' . $index;
        $required = $field['field_required'] === 'yes' ? 'required' : '';
        $allowed_types = !empty($field['allowed_file_types']) ? $field['allowed_file_types'] : '';
        $max_size = !empty($field['max_file_size']) ? intval($field['max_file_size']) : 5; // MB

        ?>
        <input type="file" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>"
            class="sep-field sep-file-field" <?php echo $required; ?> data-max-size="<?php echo esc_attr($max_size); ?>"
            data-allowed-types="<?php echo esc_attr($allowed_types); ?>" accept="<?php echo esc_attr($allowed_types); ?>">
        <small class="sep-field-help">
            <?php printf(__('Max file size: %s MB', 'super-elem-pro'), $max_size); ?>
        </small>
        <?php
    }

    public static function validate($file)
    {
        if (empty($file['name'])) {
            return '';
        }

        // Check file size
        $max_size = 5 * 1024 * 1024; // 5MB in bytes
        if ($file['size'] > $max_size) {
            return new \WP_Error('file_too_large', __('File size exceeds the maximum allowed.', 'super-elem-pro'));
        }

        // Handle file upload
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $upload = wp_handle_upload($file, ['test_form' => false]);

        if (isset($upload['error'])) {
            return new \WP_Error('upload_error', $upload['error']);
        }

        return $upload['url'];
    }
}