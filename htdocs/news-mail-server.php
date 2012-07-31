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

function index__error_handler ($errno, $errstr) {
    throw new ErrorException(sprintf('[%s] %s', $errno, $errstr));
}

if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
}

set_error_handler('index__error_handler');

require_once dirname(__FILE__).'/../news-mail-server.php';
news_mail_server__main();
