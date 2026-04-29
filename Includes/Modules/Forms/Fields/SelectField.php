<?php
namespace SuperElemPro\Modules\Forms\Fields;

if (!defined('ABSPATH'))
    exit;

/**
 * Select Field
 * 
 * @since 1.0.0
 */
class SelectField
{

    public static function render($field, $index)
    {
        $field_id = 'field-' . $index;
        $field_name = !empty($field['field_name']) ? $field['field_name'] : 'field_' . $index;
        $required = $field['field_required'] === 'yes' ? 'required' : '';
        $options = !empty($field['field_options']) ? explode("\n", $field['field_options']) : [];

        ?>
        <select id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>"
            class="sep-field sep-select-field" <?php echo $required; ?> data-validation="select">
            <option value=""><?php echo esc_html($field['field_placeholder']); ?></option>
            <?php foreach ($options as $option): ?>
                <?php $option = trim($option); ?>
                <option value="<?php echo esc_attr($option); ?>" <?php selected($field['field_default_value'], $option); ?>>
                    <?php echo esc_html($option); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public static function validate($value)
    {
        return sanitize_text_field($value);
    }
}