<?php 
    include 'CSV.php';

    use csvAppend\CSV;

    $sourceFile1 = new CSV($argv[1]);
    $sourceFile2 = new CSV($argv[2]);

    if ($sourceFile1->isValid() && $sourceFile2->isValid()) {
        echo "\"email_hash\",\"category\",\"filename\"\n";
        $sourceFile1->appendCSV();
        $sourceFile2->appendCSV();
    }

