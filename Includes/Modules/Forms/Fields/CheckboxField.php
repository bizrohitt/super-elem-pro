<?php
namespace SuperElemPro\Modules\Forms\Fields;

if (!defined('ABSPATH'))
    exit;

/**
 * Checkbox Field
 * 
 * @since 1.0.0
 */
class CheckboxField
{

    public static function render($field, $index)
    {
        $field_name = !empty($field['field_name']) ? $field['field_name'] : 'field_' . $index;
        $required = $field['field_required'] === 'yes' ? 'required' : '';
        $options = !empty($field['field_options']) ? explode("\n", $field['field_options']) : [];

        ?>
        <div class="sep-checkbox-group">
            <?php foreach ($options as $opt_index => $option): ?>
                <?php $option = trim($option); ?>
                <label class="sep-checkbox-label">
                    <input type="checkbox" name="<?php echo esc_attr($field_name); ?>[]"
                        value="<?php echo esc_attr($option); ?>" class="sep-checkbox" <?php echo $required; ?>>
                    <span><?php echo esc_html($option); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <?php
    }

    public static function validate($value)
    {
        if (is_array($value)) {
            return array_map('sanitize_text_field', $value);
        }
        return sanitize_text_field($value);
    }
}