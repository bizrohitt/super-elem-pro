<?php
namespace SuperElemPro\Modules\Forms\Fields;

if (!defined('ABSPATH'))
    exit;

/**
 * Date Field
 * 
 * @since 1.0.0
 */
class DateField
{

    public static function render($field, $index)
    {
        $field_id = 'field-' . $index;
        $field_name = !empty($field['field_name']) ? $field['field_name'] : 'field_' . $index;
        $required = $field['field_required'] === 'yes' ? 'required' : '';

        ?>
        <input type="date" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>"
            value="<?php echo esc_attr($field['field_default_value']); ?>" class="sep-field sep-date-field" <?php echo $required; ?>>
        <?php
    }

    public static function validate($value)
    {
        return sanitize_text_field($value);
    }
}