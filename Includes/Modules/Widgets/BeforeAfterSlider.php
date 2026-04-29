<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class BeforeAfterSlider extends Widget_Base
{

    public function get_name()
    {
        return 'sep-before-after';
    }

    public function get_title()
    {
        return __('Before After Slider', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-image-before-after';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_images',
            [
                'label' => __('Images', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'before_image',
            [
                'label' => __('Before Image', 'super-elem-pro'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'after_image',
            [
                'label' => __('After Image', 'super-elem-pro'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'before_label',
            [
                'label' => __('Before Label', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Before', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'after_label',
            [
                'label' => __('After Label', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('After', 'super-elem-pro'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        ?>
        <div class="sep-before-after" id="sep-ba-<?php echo esc_attr($widget_id); ?>">
            <div class="sep-before-after-container">
                <img src="<?php echo esc_url($settings['before_image']['url']); ?>" alt="Before" class="sep-ba-before">
                <div class="sep-ba-after-wrapper" style="width: 50%;">
                    <img src="<?php echo esc_url($settings['after_image']['url']); ?>" alt="After" class="sep-ba-after">
                </div>
                <div class="sep-ba-handle" style="left: 50%;">
                    <span class="sep-ba-arrow-left">◀</span>
                    <span class="sep-ba-divider"></span>
                    <span class="sep-ba-arrow-right">▶</span>
                </div>
            </div>
            <div class="sep-ba-labels">
                <span class="sep-ba-label sep-ba-label-before"><?php echo esc_html($settings['before_label']); ?></span>
                <span class="sep-ba-label sep-ba-label-after"><?php echo esc_html($settings['after_label']); ?></span>
            </div>
        </div>

        <script>
            (function () {
                var container = document.getElementById('sep-ba-<?php echo esc_js($widget_id); ?>');
                var handle = container.querySelector('.sep-ba-handle');
                var afterWrapper = container.querySelector('.sep-ba-after-wrapper');
                var isDragging = false;

                handle.addEventListener('mousedown', function () {
                    isDragging = true;
                });

                document.addEventListener('mouseup', function () {
                    isDragging = false;
                });

                container.addEventListener('mousemove', function (e) {
                    if (!isDragging) return;

                    var rect = container.getBoundingClientRect();
                    var x = e.clientX - rect.left;
                    var percent = (x / rect.width) * 100;

                    if (percent >= 0 && percent <= 100) {
                        afterWrapper.style.width = percent + '%';
                        handle.style.left = percent + '%';
                    }
                });
            })();
        </script>
        <?php
    }
}