<?php
include_once 'Pagination.php';
$page = new Pagination();
echo $page->page(200,1);