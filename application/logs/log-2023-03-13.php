<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-03-13 08:58:34 --> 404 Page Not Found: Sounds/sound6.ogg
ERROR - 2023-03-13 13:54:02 --> Severity: Notice --> Undefined variable: shift C:\xampp\htdocs\TataToyo\application\views\Batches\Verify.php 109
ERROR - 2023-03-13 14:46:16 --> Query error: Column 'CBID' in order clause is ambiguous - Invalid query: SELECT *
FROM `createbatchwc`
JOIN `consigneemaster` ON `consigneemaster`.`CMID` = `createbatchwc`.`CMID`
JOIN `productmaster` ON `productmaster`.`id` = `createbatchwc`.`PID`
JOIN `qtymasterwc` ON `qtymasterwc`.`CBID` = `createbatchwc`.`CBID`
WHERE `createbatchwc`.`BitStatus` = 1
ORDER BY `CBID` DESC
