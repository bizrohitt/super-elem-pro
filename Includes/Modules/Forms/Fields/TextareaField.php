<?php
namespace SuperElemPro\Modules\Forms\Fields;

if (!defined('ABSPATH'))
    exit;

/**
 * Textarea Field
 * 
 * @since 1.0.0
 */
class TextareaField
{

    public static function render($field, $index)
    {
        $field_id = 'field-' . $index;
        $field_name = !empty($field['field_name']) ? $field['field_name'] : 'field_' . $index;
        $required = $field['field_required'] === 'yes' ? 'required' : '';
        $rows = !empty($field['rows']) ? intval($field['rows']) : 5;

        ?>
        <textarea id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>"
            placeholder="<?php echo esc_attr($field['field_placeholder']); ?>" class="sep-field sep-textarea-field"
            rows="<?php echo esc_attr($rows); ?>" <?php echo $required; ?>
            data-validation="textarea"><?php echo esc_textarea($field['field_default_value']); ?></textarea>
        <?php
    }

    public static function validate($value)
    {
        return sanitize_textarea_field($value);
    }
}