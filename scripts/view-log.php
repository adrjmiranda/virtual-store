<?php

$log = $argv[1] ?? 'app.log';
$path = "/var/store/logs/{$log}";

echo "Reading log: {$path}\n\n";

passthru("docker exec -it php-app tail -f {$path}");