<?php
namespace SuperElemPro\Modules\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH'))
    exit;

class DataTable extends Widget_Base
{

    public function get_name()
    {
        return 'sep-data-table';
    }

    public function get_title()
    {
        return __('Data Table', 'super-elem-pro');
    }

    public function get_icon()
    {
        return 'eicon-table';
    }

    public function get_categories()
    {
        return ['super-elem-pro'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_header',
            [
                'label' => __('Table Header', 'super-elem-pro'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'header_text',
            [
                'label' => __('Text', 'super-elem-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Header', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'header_cells',
            [
                'label' => __('Header Cells', 'super-elem-pro'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['header_text' => __('Column 1', 'super-elem-pro')],
                    ['header_text' => __('Column 2', 'super-elem-pro')],
                    ['header_text' => __('Column 3', 'super-elem-pro')],
                ],
                'title_field' => '{{{ header_text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_rows',
            [
                'label' => __('Table Rows', 'super-elem-pro'),
            ]
        );

        $this->add_control(
            'table_data',
            [
                'label' => __('Table Data (CSV)', 'super-elem-pro'),
                'type' => Controls_Manager::TEXTAREA,
                'description' => __('Enter data in CSV format. One row per line, comma-separated values.', 'super-elem-pro'),
                'default' => "Value 1, Value 2, Value 3\nValue 4, Value 5, Value 6",
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $rows = explode("\n", $settings['table_data']);
        ?>
        <table class="sep-data-table">
            <thead>
                <tr>
                    <?php foreach ($settings['header_cells'] as $cell): ?>
                        <th><?php echo esc_html($cell['header_text']); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <?php $cells = str_getcsv($row); ?>
                    <tr>
                        <?php foreach ($cells as $cell): ?>
                            <td><?php echo esc_html(trim($cell)); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
}