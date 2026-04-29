<?php
namespace SuperElemPro\Modules\Forms\Fields;

if (!defined('ABSPATH'))
    exit;

/**
 * Hidden Field
 * 
 * @since 1.0.0
 */
class HiddenField
{

    public static function render($field, $index)
    {
        $field_name = !empty($field['field_name']) ? $field['field_name'] : 'field_' . $index;

        ?>
        <input type="hidden" name="<?php echo esc_attr($field_name); ?>"
            value="<?php echo esc_attr($field['field_default_value']); ?>">
        <?php
    }

    public static function validate($value)
    {
        return sanitize_text_field($value);
    }
}