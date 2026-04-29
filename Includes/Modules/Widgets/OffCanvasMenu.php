<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class OffCanvasMenu extends Widget_Base
{

    public function get_name()
    {
        return 'sep-offcanvas-menu';
    }

    public function get_title()
    {
        return __('Off-Canvas Menu', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-menu-bar';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_menu',
            [
                'label' => __('Menu', 'super-elem-pro'),
            ]
        );

        $menus = wp_get_nav_menus();
        $menu_options = [];
        foreach ($menus as $menu) {
            $menu_options[$menu->term_id] = $menu->name;
        }

        $this->add_control(
            'menu',
            [
                'label' => __('Select Menu', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => $menu_options,
            ]
        );

        $this->add_control(
            'position',
            [
                'label' => __('Position', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'left' => __('Left', 'super-elem-pro'),
                    'right' => __('Right', 'super-elem-pro'),
                ],
                'default' => 'left',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <button class="sep-offcanvas-trigger">☰</button>

        <div class="sep-offcanvas sep-offcanvas-<?php echo esc_attr($settings['position']); ?>">
            <button class="sep-offcanvas-close">×</button>
            <?php
            if (!empty($settings['menu'])) {
                wp_nav_menu([
                    'menu' => $settings['menu'],
                    'container' => 'nav',
                    'container_class' => 'sep-offcanvas-nav',
                ]);
            }
            ?>
        </div>

        <div class="sep-offcanvas-overlay"></div>

        <script>
            jQuery(document).ready(function ($) {
                $('.sep-offcanvas-trigger').on('click', function () {
                    $('.sep-offcanvas').addClass('active');
                    $('.sep-offcanvas-overlay').addClass('active');
                });

                $('.sep-offcanvas-close, .sep-offcanvas-overlay').on('click', function () {
                    $('.sep-offcanvas').removeClass('active');
                    $('.sep-offcanvas-overlay').removeClass('active');
                });
            });
        </script>
        <?php
    }
}