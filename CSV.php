<?php

namespace csvAppend;

class CSV 
{
    private $fullpath;
    private $filename;

    public function __construct($fullpath) {
        $this->fullpath = $fullpath;
        $this->filename = basename($fullpath);
    }

    public function isValid() {
        if (!file_exists($this->fullpath)) {
            echo "Error: {$this->fullpath} not found, please check filename and path and try again.\n";
            return false;
        }
        return true;
    }

    public function appendCSV () {
        $row = 0;
        if (($readHandle = fopen($this->fullpath, "r")) !== false) {
            while (($data = fgetcsv($readHandle, 0, ",")) !== FALSE) {
                if ($row == 0) {
                    if (trim($data[0] != 'email_hash') || trim($data[1] != 'category')) {
                        echo "unexpected header in {$this->filename}, aborting.";
                        die();
                    }
                } else {
                    $num = count($data);
                    for ($c=0; $c<$num; $c++) {
                        echo "\"".trim($data[$c])."\",";
                    }   
                    echo "\"{$this->filename}\"\n";            
                }
                $row++;
                }
            }
        fclose($readHandle);
    }
}