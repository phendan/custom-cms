<?php

// Dump and die
function dd(mixed ...$values) {
    echo '<pre>', var_dump(...$values), '</pre>';
    die();
}

function d(mixed ...$values) {
    echo '<pre>', var_dump(...$values), '</pre>';
}
