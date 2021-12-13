<?php
require_once("lib\log.php");
use BabyLog\BabyLog;

function logtest() {
    BabyLog::trace("aiueo");
}

logtest();
BabyLog::trace("next");