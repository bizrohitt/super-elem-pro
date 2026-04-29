<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class TestimonialCarousel extends Widget_Base
{

    public function get_name()
    {
        return 'sep-testimonial-carousel';
    }

    public function get_title()
    {
        return __('Testimonial Carousel', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-testimonial-carousel';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    public function get_script_depends()
    {
        return ['swiper'];
    }

    public function get_style_depends()
    {
        return ['swiper'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_testimonials',
            [
                'label' => __('Testimonials', 'super-elem-pro'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'name',
            [
                'label' => __('Name', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('John Doe', 'super-elem-pro'),
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __('Title', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('CEO, Company', 'super-elem-pro'),
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label' => __('Content', 'super-elem-pro'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'super-elem-pro'),
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __('Image', 'super-elem-pro'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'rating',
            [
                'label' => __('Rating', 'super-elem-pro'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5,
                'default' => 5,
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => __('Testimonials', 'super-elem-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'name' => __('John Doe', 'super-elem-pro'),
                        'title' => __('CEO', 'super-elem-pro'),
                        'content' => __('Great service!', 'super-elem-pro'),
                    ],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->end_controls_section();

        // Carousel Settings
        $this->start_controls_section(
            'section_carousel',
            [
                'label' => __('Carousel Settings', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'super-elem-pro'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed (ms)', 'super-elem-pro'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="sep-testimonial-carousel swiper">
            <div class="swiper-wrapper">
                <?php foreach ($settings['testimonials'] as $item): ?>
                    <div class="swiper-slide">
                        <div class="sep-testimonial-item">
                            <?php if (!empty($item['image']['url'])): ?>
                                <div class="sep-testimonial-image">
                                    <img src="<?php echo esc_url($item['image']['url']); ?>"
                                        alt="<?php echo esc_attr($item['name']); ?>">
                                </div>
                            <?php endif; ?>

                            <div class="sep-testimonial-content">
                                <p><?php echo esc_html($item['content']); ?></p>
                            </div>

                            <div class="sep-testimonial-author">
                                <h4><?php echo esc_html($item['name']); ?></h4>
                                <p><?php echo esc_html($item['title']); ?></p>
                            </div>

                            <?php if (!empty($item['rating'])): ?>
                                <div class="sep-testimonial-rating">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <span class="star <?php echo $i < $item['rating'] ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>

        <script>
            new Swiper('.sep-testimonial-carousel', {
                loop: true,
                autoplay: <?php echo $settings['autoplay'] === 'yes' ? 'true' : 'false'; ?>,
                speed: <?php echo intval($settings['autoplay_speed']); ?>,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                }
            });
        </script>
        <?php
    }
}