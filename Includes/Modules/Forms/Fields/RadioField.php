<?php
namespace SuperElemPro\Modules\Forms\Fields;

if (!defined('ABSPATH'))
    exit;

/**
 * Radio Field
 * 
 * @since 1.0.0
 */
class RadioField
{

    public static function render($field, $index)
    {
        $field_name = !empty($field['field_name']) ? $field['field_name'] : 'field_' . $index;
        $required = $field['field_required'] === 'yes' ? 'required' : '';
        $options = !empty($field['field_options']) ? explode("\n", $field['field_options']) : [];

        ?>
        <div class="sep-radio-group">
            <?php foreach ($options as $opt_index => $option): ?>
                <?php $option = trim($option); ?>
                <label class="sep-radio-label">
                    <input type="radio" name="<?php echo esc_attr($field_name); ?>" value="<?php echo esc_attr($option); ?>"
                        class="sep-radio" <?php checked($field['field_default_value'], $option); ?>             <?php echo $required; ?>>
                    <span><?php echo esc_html($option); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <?php
    }

    public static function validate($value)
    {
        return sanitize_text_field($value);
    }
}