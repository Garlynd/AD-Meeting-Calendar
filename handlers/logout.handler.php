<?php
require_once '../utils/auth.util.php';

Auth::logout();

header("Location: /pages/login/index.php");
exit;