<?php 

$debug = 0;
$logFile = "csv-combiner-log.txt";

// To allow for troubleshooting/feedback, the use of a logging file is created here.

// ** ** ** Functions
    function logData($file, $data) {
        $log_msg = $data." - ".date('d-M-Y h:i:s')."\n";
        file_put_contents($file, $log_msg, FILE_APPEND);
    }

    if ($debug) {
        $start = strtotime("now");
        logData($logFile, "** ** ** CSV combiner program called.".implode(" ", $argv));
    }

    // ***** verify executing statement, filenames, and files for proper formatting
    if ($debug && $argc < 3) {
    logData($logFile, "Error: Incorrect number of filenames. This script requires 2 source files to be passed, ".($argc-1)." entered.");
    die();
    } 

    // check source files exist and formatted as expected
    if ($debug && (!file_exists($argv[1]) || !file_exists($argv[2]))) {
        logData($logFile, "Error: 1 or more of the files do not exists: check '{$argv[1]}' or '{$argv[2]}'");
        die();
    } 

$sourceFile[1] = basename($argv[1]);
$sourceFile[2] = basename($argv[2]);

echo "\"email_hash\",\"category\",\"filename\"\n";

for ($i=1; $i<3; $i++) {
    $row = 0;
    if (($readHandle = fopen($argv[$i], "r")) !== false) {
        while (($data = fgetcsv($readHandle, 0, ",")) !== FALSE) {
            if ($row == 0) {
                if (trim($data[0] != 'email_hash') || trim($data[1] != 'category')) {
                    if ($debug) {
                        logData($logFile, "Error: Unexected header in {$sourceFile[$i]}");
                        logData($logFile, "{$data[0]} and {$data[1]}");
                    }
                    die();
                }
            } else {
                $num = count($data);
                for ($c=0; $c<$num; $c++) {
                    echo "\"".trim($data[$c])."\",";
                }   
                echo "\"{$sourceFile[$i]}\"\n";            
            }
            if ($debug && $row % 1000 == 0) {
                file_put_contents($logFile, '.', FILE_APPEND);
                if ($row % 110000 == 0) {
                   file_put_contents($logFile, $row."\r\n", FILE_APPEND); 
                }
            }
            $row++;
            }
        }
    fclose($readHandle);

    if ($debug) {
        $end = strtotime("now");
        $processingTime = ($end - $start)/60;
        logData($logFile, "\r\n{$sourceFile[$i]} completed, ".($row-1)." rows added to output file.\r\n");
        logData($logFile, "It took {$processingTime} minutes. \r\n");
    }
}