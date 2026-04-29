<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH'))
    exit;

class Charts extends Widget_Base
{

    public function get_name()
    {
        return 'sep-charts';
    }

    public function get_title()
    {
        return __('Charts', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-chart';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    public function get_script_depends()
    {
        return ['chart-js'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_chart',
            [
                'label' => __('Chart', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'chart_type',
            [
                'label' => __('Type', 'super-elem-pro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'bar' => __('Bar', 'super-elem-pro'),
                    'line' => __('Line', 'super-elem-pro'),
                    'pie' => __('Pie', 'super-elem-pro'),
                    'doughnut' => __('Doughnut', 'super-elem-pro'),
                ],
                'default' => 'bar',
            ]
        );

        $this->add_control(
            'chart_labels',
            [
                'label' => __('Labels (comma-separated)', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Jan, Feb, Mar, Apr, May, Jun',
            ]
        );

        $this->add_control(
            'chart_data',
            [
                'label' => __('Data (comma-separated)', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => '12, 19, 3, 5, 2, 3',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        $labels = array_map('trim', explode(',', $settings['chart_labels']));
        $data = array_map('intval', explode(',', $settings['chart_data']));
        ?>
        <canvas id="sep-chart-<?php echo esc_attr($widget_id); ?>"></canvas>

        <script>
            new Chart(document.getElementById('sep-chart-<?php echo esc_js($widget_id); ?>'), {
                type: '<?php echo esc_js($settings['chart_type']); ?>',
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: 'Dataset',
                        data: <?php echo json_encode($data); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        </script>
        <?php
    }
}