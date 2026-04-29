<?php
/**
 * Footer Document
 *
 * @package SuperElemPro\Modules\ThemeBuilder\Documents
 * @author  Rohit
 * @since   1.0.0
 */

namespace SuperElemPro\Modules\ThemeBuilder\Documents;

use Elementor\Core\Base\Document;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Footer
 */
class Footer extends Document
{

    /**
     * @return array
     */
    public static function get_properties(): array
    {
        $properties = parent::get_properties();
        $properties['support_kit'] = true;
        $properties['show_in_finder'] = true;
        $properties['support_conditions'] = true;
        return $properties;
    }

    /**
     * @return string
     */
    public static function get_title(): string
    {
        return __('SEP Footer', 'super-elem-pro');
    }
}