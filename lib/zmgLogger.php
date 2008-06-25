<?php

class zmgLogger {
    var $_logDir      = "";

    var $_logFile     = "out_";

    var $_countFile   = "counter";

    var $_headerTitle = "LOG";

    var $_logMode     = "";

    var $_logNumber   = 0;

    var $_callCount   = 0;

    /**
     * Class constructor.
     * Prepares files for first run.
     * Global variables setting.
     *
     * @return void
     */
    function zmgLogger($logDir = "log", $logMode = "oneFile") {
        //set global variables
        $this->_logDir     = $logDir;
        $this->_logMode    = $logMode;

        $this->_countFile  = $this->_logDir . DS . $this->_countFile;

        zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');

        //verify log folder existence. If it doesn't I create it
        if (!is_dir($this->_logDir)) {
            if (!zmgFileHelper::createDir($this->_logDir)) {
                zmgError::throwError("Could not create log dir");
            }
        }

        //Counter init
        if (!zmgFileHelper::exists($this->_countFile)) {
            //if log counter file does not exist, I create it
            touch($this->_countFile);

            //inicializing file in 0
            $initNumber = 0;
            $fp         = fopen($this->_countFile, "a");

            if (fwrite($fp, $initNumber) === false) {
                zmgError::throwError("Could not write Counter file");
            }
            fclose($fp);
        }

        //read counter
        $logNumber = intval(trim(file_get_contents($this->_countFile)));
        $logNumber++; //increment counter

        //set log number in class var
        $this->_logNumber = $logNumber;
        //write incremented counter value
        $fp = fopen($this->_countFile, "w+");

        if (fwrite($fp, $logNumber) === false) {
            zmgError::throwError("Could not write Counter file");
        }
        fclose($fp);
    }

    /**
     * Recieves the string you want lo log. This function is used by "logThis"
     * function, which offers simplified logging with some practical functions.
     *
     * @param String $logString
     * @return void
     */
    function writeLog($logString) {
        global $logNumber;

        //depending on selected log mode...
        //use only one log file, or one file per log instance
        $logFile = $this->_logDir . DS . $this->_logFile . ".log";
        if ($this->_logMode == "oneFilePerLog") {
            $logFile = $this->_logDir . DS . $this->_logFile . $this->_logNumber . ".log";
        }

        //in case file does not exist
        if (!zmgFileHelper::exists($logFile)) {
            //if log file does not exist, I create it
            touch($logFile);

            //generate file header
            $logHeader = $this->_headerTitle . "\n"
             . "--------------------------------------------------------------------\n"
             . "--------------------------------------------------------------------\n\n\n";

            $fp = fopen($logFile, "w+");
            if (fwrite($fp, $logHeader) === false) {
                zmgError::throwError("Could not write LOG Header");
            }
            fclose($fp);
        }

        //write to log file
        $fp = fopen($logFile, "a");
        if (fwrite($fp, $logString) === false) {
            zmgError::throwError("Could not write to LOG file");
        }
        fclose($fp);
    }

    /**
     * Writes to LOG File each recieved value. To write the log we use the
     * function "writeLog".
     * Output: $this->writeLog() will directly write to log file
     *
     * @param String $string
     * @param String $modifier
     * @return void
     */
    function append($string, $modifier = "empty") {
        //set "line" separator
        $line = "\n--------------------------------------------------------------------\n";

        //it uses modifiers only if a log function has not been passed
        if (substr($string, 0, 2) != "f:") {
            $string = $this->getFormattedDate() . " - " . $string;
            switch ($modifier) {
                case "empty":
                    $this->writeLog($string . "\n");
                    break;
                case "n":
                    $this->writeLog($string . "\n");
                    break;
                case "2n":
                    $this->writeLog($string . "\n\n");
                    break;
                case "3n":
                    $this->writeLog($string . "\n\n\n");
                    break;
                case "line":
                    $this->writeLog($string . $line);
                    break;
                case "2line":
                    $this->writeLog($string . $line . $line);
                    break;
                case "nLine":
                    $this->writeLog($string . "\n" . $line);
                    break;
                case "2nLine":
                    $this->writeLog($string . "\n\n" . $line);
                    break;
                case "n2Line":
                    $this->writeLog($string . "\n" . $line . $line);
                    break;
            }
        } else {
            //FUNCTIONS - "F:"
            //using a log function passed in $string
            //example: logThis("f:line")
            switch ($string) {
                case "f:line":
                    $this->writeLog($line);
                    break;
                case "f:2line":
                    $this->writeLog($line . $line);
                    break;
                case "f:nl":
                    $this->writeLog("\n");
                    break;
                case "f:2nl":
                    $this->writeLog("\n\n");
                    break;
                case "f:logNumber":
                    $this->writeLog("+ LOG Number: " . $this->_logNumber . "\n");
                    break;
                case "f:counter":
                    switch($modifier){
                        case "empty":
                            $this->_callCount++;
                            $this->writeLog($this->_callCount);
                            break;
                        default:
                            $this->_callCount++;
                            $this->writeLog($modifier . $this->_callCount);
                            break;
                    }
                    break;
                case "f:counter.nl":
                    switch ($modifier) {
                        case "empty":
                            $this->_callCount++;
                            $this->writeLog($this->_callCount . "\n");
                            break;
                        default:
                            $this->_callCount++;
                            $this->writeLog($modifier . $this->_callCount . "\n");
                            break;
                    }
                    break;
                case "f:nl.counter":
                    switch ($modifier) {
                        case "empty":
                            $this->_callCount++;
                            $this->writeLog("\n" . $this->_callCount);
                            break;
                        default:
                            $this->_callCount++;
                            $this->writeLog("\n" . $modifier . $this->_callCount);
                            break;
                    }
                    break;
                case "f:nl.counter.nl":
                    switch ($modifier) {
                        case "empty":
                            $this->_callCount++;
                            $this->writeLog("\n" . $this->_callCount . "\n");
                            break;
                        default:
                            $this->_callCount++;
                            $this->writeLog("\n" . $modifier . $this->_callCount . "\n");
                            break;
                    }
                    break;
            }
        }
    }

    /**
     * Return formatted actual date.
     * Example: 28.08.2005 - 01:14
     *
     * @return String $datef
     */
    function getFormattedDate() {
        return date("Y.m.d - H:i");
    }

    /**
     * Deletes log dir and its contents.
     *
     * @return void
     */
    function clean(){
        zmgFileHelper::deleteDir($this->_logDir);
    }
}

?>
