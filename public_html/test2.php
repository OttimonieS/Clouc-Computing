<?php
session_start();
echo $_SESSION['cek'] ?? 'Session tidak terbaca';
