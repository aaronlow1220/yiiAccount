<?php

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => true,
    'rules' => array_merge(
        require __DIR__ . '/routes/v1.php'
    ),
];