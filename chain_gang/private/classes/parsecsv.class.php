<?php

class ParseCsv
{
    //PROPS
    public static $delimiter = ','; //comma seperated values
    private $filename;
    private $header;
    private $data = [];
    private $row_count = 0;

    //METHODS
    public function __construct($filename = '')
    {
        //generating if there is nothing '' == null?
        if ($filename != '') {
            $this->filename = $filename;
        }
    }

    //check if the file actually exists
    public function file($filename)
    {
        if(!file_exists($filename))
        {
            echo "File does not exist.";
            return false; //there is no file
        } elseif(!is_readable($filename)){
            echo "File is not readable.";
            return false; //the file is not readable and doesnt exist
        }
        $this->filename = $filename;
        return true; //success! the csv exists
    }

    //PARSE CSV FILE INTO HTML
    public function parse()
    {
        if(!isset($this->filename))
        {
            echo "File is not set.";
            return false; //doesnt keep going with the parse
        }

        //clear any previous results
        $this->reset();

        $file = fopen($this->filename, 'r'); //read
        //!feof checks for not the end of a file fendoffile
        while (!feof($file)) {
            //parses csv line by line not just one line from the file like normal fget
            $row = fgetcsv($file, 0, self::$delimiter);
            if ($row == [NULL] || $row === FALSE) {
                continue;
            }
            //sets header to the row if it hasnt been set before 'header' => 'row'
            if (!$this->header) {
                $this->header = $row;
            } else {
                //other wise combine this->header and row and add it to the data arr assoc style
                $this->data[] = array_combine($this->header, $row);
                $this->row_count++; //adds another increment to this static property foreach header => row
            }
        }
        fclose($file);
        return $this->data; //result is this data array from the csv
    }

    public function last_results()
    {
        return $this->data;
    }

    //return value of this row count
    public function row_count()
    {
        return $this->row_count;
    }

    //reset these properties
    private function reset()
    {
        $this->header = NULL;
        $this->data = [];
        $this->row_count = 0;
    }
}
