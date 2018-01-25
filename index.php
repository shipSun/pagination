<?php
include_once 'Pagination.php';
$page = new Pagination();
$page->setBaseUrl('http://www.ship.com/pagination?p=');
$page->setPagingNo(5);
echo <<<ETO
<style>
.current {
    background-color:red;
}
a{
    padding:10px;
}
</style>
ETO;

echo $page->page(2000,$_GET['p']);