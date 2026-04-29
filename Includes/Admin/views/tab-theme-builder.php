<?php
if (!defined('ABSPATH'))
    exit;

$theme_modules = [
    'theme-builder' => [
        'title' => __('Theme Builder', 'super-elem-pro'),
        'description' => __('Create headers, footers, single post templates, archives, and more', 'super-elem-pro'),
    ],
];

$enabled = get_option('super_elem_pro_modules', []);
?>

<div class="sep-module-grid">
    <?php foreach ($theme_modules as $module_id => $module): ?>
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