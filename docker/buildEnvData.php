<?php
$filename = '.env';

if (file_exists($filename)) {
    echo "$filename exists, not doing anything";
} else {
    $envData = getenv('ENV_DATA');

    if ($envData != null) {
        $decodedEnv = json_decode($envData, true);

        $formattedEnv = "";

        foreach($decodedEnv as $envKey => $envValue) {
            $formattedEnv .= "$envKey=\"$envValue\"\n";
        }

        echo "Writing values to $filename\n";

        // Write the contents to the file,
        // using the FILE_APPEND flag to append the content to the end of the file
        // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
        file_put_contents($filename, $formattedEnv, LOCK_EX);

        chown($filename, 'nginx');
        chgrp($filename, 'nginx');
    }
}
?>