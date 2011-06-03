<?php
echo 'login account';
$table = new Table();
$tr = new TableRow();
$td = new TableCell();
$table->addRow($tr);
$table->addRow($tr);
$table->addRow($tr);
echo $table->getTableString();
?>