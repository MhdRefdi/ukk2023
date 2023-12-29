<?php
include_once '../helpers/core.php';

if (auth()) {
    logout();
} else {
    header('location: login');
}
