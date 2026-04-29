<?php
if (!defined('ABSPATH'))
    exit;

$extensions_list = [
    'extensions' => [
        'title' => __('Extensions Pack', 'super-elem-pro'),
        'description' => __('Sticky, Motion Effects, Custom CSS/JS, Display Conditions', 'super-elem-pro'),
    ],
];

$enabled = get_option('super_elem_pro_modules', []);
?>

<div class="sep-module-grid">
    <?php foreach ($extensions_list as $module_id => $module): ?>
        <div class="sep-module-card">
            <div class="sep-module-header">
                <label class="sep-switch">
                    <input type="checkbox" name="super_elem_pro_modules[]" value="<?php echo esc_attr($module_id); ?>"
                        <?php checked(in_array($module_id, $enabled)); ?>>
                    <span class="sep-slider"></span>
                </label>
                <h3><?php echo esc_html($module['title']); ?></h3>
            </div>
            <p class="sep-module-desc"><?php echo esc_html($module['description']); ?></p>
        </div>
    <?php endforeach; ?>
</div>