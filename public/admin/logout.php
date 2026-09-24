<?php
require_once __DIR__ . '/../../src/bootstrap.php';

adminLogout();
redirect('/admin/login.php');
