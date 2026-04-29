<?php
namespace SuperElemPro\Modules\Forms\Fields;

if (!defined('ABSPATH'))
    exit;

/**
 * Text Field
 * 
 * @since 1.0.0
 */
class TextField
{

    public static function render($field, $index)
    {
        $field_id = 'field-' . $index;
        $field_name = !empty($field['field_name']) ? $field['field_name'] : 'field_' . $index;
        $required = $field['field_required'] === 'yes' ? 'required' : '';

        ?>
        <input type="text" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>"
            placeholder="<?php echo esc_attr($field['field_placeholder']); ?>"
            value="<?php echo esc_attr($field['field_default_value']); ?>" class="sep-field sep-text-field" <?php echo $required; ?> data-validation="text">
        <?php
    }

    public static function validate($value)
    {
        return sanitize_text_field($value);
    }
}