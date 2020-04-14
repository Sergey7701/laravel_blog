<?php
if (!function_exists('flash')) {
/**
 * 
 * @param string $message - сообщения для вывода
 * @param string $type суффикс для bootstrap-стиля alert- (alert-success)
 */
    function flash(string $message, string $type = 'success')
    {
        session()->flash('message', $message);
        session()->flash('message_type', $type);
    }
}
