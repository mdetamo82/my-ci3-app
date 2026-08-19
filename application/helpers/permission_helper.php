<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Safely encodes a value for HTML output.
 *
 * @param mixed $input
 * @param bool  $preserveLineBreaks
 * @return string
 */
function safe_html($input, bool $preserveLineBreaks = false): string
{
    if ($input === null) {
        return '';
    }

    if (is_bool($input)) {
        return $input ? 'true' : 'false';
    }

    if (
        is_array($input) ||
        (is_object($input) && !method_exists($input, '__toString'))
    ) {
        throw new InvalidArgumentException(
            'Cannot sanitize array/object as HTML'
        );
    }

    $string = (string) $input;

    if (!mb_check_encoding($string, 'UTF-8')) {
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');

        if (!mb_check_encoding($string, 'UTF-8')) {
            throw new InvalidArgumentException(
                'Invalid UTF-8 string after conversion attempt'
            );
        }
    }

    if ($preserveLineBreaks) {
        $string = nl2br($string, false);
    }

    return htmlspecialchars(
        $string,
        ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
        'UTF-8',
        false
    );
}


/**
 * Check whether the current user has a permission.
 *
 * This function delegates authorization to the centralized
 * Authorization library.
 *
 * @param string $permission
 * @return bool
 */
function has_permission($permission)
{
    $CI =& get_instance();

    $CI->load->library('authorization');

    return $CI->authorization->can($permission);
}


/**
 * Require a permission.
 *
 * This is intended for controller authorization boundaries.
 *
 * @param string $permission
 * @return void
 */
function require_permission($permission)
{
    $CI =& get_instance();

    $CI->load->library('authorization');

    $CI->authorization->require_permission($permission);
}
