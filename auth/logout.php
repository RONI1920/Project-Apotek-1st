<?php
session_start();
session_destroy();
header("Location: login.php?timeout=true");
exit;
