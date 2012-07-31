<?php
// -*- mode: php; coding: utf-8 -*-
//
// Copyright 2012 Andrej A Antonov <polymorphm@gmail.com>
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

function news_mail_server__get_param ($name) {
    if (!array_key_exists($name, $_REQUEST)) {
        return NULL;
    }
    
    $value = $_REQUEST[$name];
    
    if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    
    return $value;
}

function news_mail_server__get_json_param ($name) {
    $value = news_mail_server__get_param($name);
    
    if (!$value) {
        return NULL;
    }
    
    return json_decode($value, TRUE);
}

function news_mail_server__get_arr_item ($arr, $name) {
    if (!$arr || !is_array($arr) || !array_key_exists($name, $arr)) {
        return NULL;
    }
    
    return $arr[$name];
}

function news_mail_server__get_arr_arr ($arr, $name) {
    $value = news_mail_server__get_arr_item($arr, $name);
    
    if (!is_array($value)) {
        return NULL;
    }
    
    return $value;
}

function news_mail_server__get_arr_str ($arr, $name) {
    $value = news_mail_server__get_arr_item($arr, $name);
    
    if (!is_string($value)) {
        return NULL;
    }
    
    return $value;
}

function news_mail_server__check_key($key) {
    $key_file = dirname(__FILE__).'/news-mail-server.key';
    $orig_key = trim(file_get_contents($key_file));
    
    if ($orig_key && $orig_key == $key) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function news_mail_server__main () {
    $key = news_mail_server__get_param('key');
    
    if (!$key || !news_mail_server__check_key($key)) {
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode(array(
            'error' => 'KeyError',
        ));
        return;
    }
    
    $data = news_mail_server__get_json_param('data');
    $mail = news_mail_server__get_arr_arr($data, 'mail');
    $to = news_mail_server__get_arr_str($mail, 'to');
    $subject = news_mail_server__get_arr_str($mail, 'subject');
    $message = news_mail_server__get_arr_str($mail, 'message');
    $headers = news_mail_server__get_arr_str($mail, 'headers');
    
    $result = mail($to, $subject, $message, $headers);
    
    if (!$result) {
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode(array(
            'error' => 'MailError',
        ));
        return;
    }
    
    header('Content-Type: application/json;charset=utf-8');
    echo json_encode(array(
        'error' => NULL,
    ));
}
