<?php

function formatTime($sec){
    if($sec > 100){
        $sec /= 60;
        if($sec > 100){
            $sec /= 60;
            return number_format($sec) . " hr";
        }
        return number_format($sec) . " min";
    }
    return number_format($sec) . " sec";
}

function show_status($done, $total, $size=30, $lineWidth=-1, $className= '') {
    if($lineWidth <= 0){
        $lineWidth = $_ENV['COLUMNS'];
    }

    static $start_time;

    // to take account for [ and ]
    $size -= 3;
    // if we go over our bound, just ignore it
    if($done > $total) return;

    if(empty($start_time)) $start_time=time();
    $now = time();

    $perc=(double)($done/$total);

    $bar=floor($perc*$size);

    // jump to the begining
    echo "\r";
    // jump a line up
    echo "\x1b[A";
    

    $status_bar="[";
    $status_bar.=str_repeat("=", $bar);
    if($bar<$size){
        $status_bar.=">";
        $status_bar.=str_repeat(" ", $size-$bar);
    } else {
        $status_bar.="=";
    }

    $disp=number_format($perc*100, 0);

    $status_bar.="]";
    $details = "$disp%  $done/$total Class: '{$className}'  ";

    $rate = ($now-$start_time)/$done;
    $left = $total - $done;
    $eta = round($rate * $left, 2);

    $elapsed = $now - $start_time;


    $details .= " " . formatTime($eta)." ". formatTime($elapsed);

    $lineWidth--;
    if(strlen($details) >= $lineWidth){
        $details = substr($details, 0, $lineWidth-1);
    }
    else
    {
        $countSpaceLine = $lineWidth - strlen($details);
        for ($spaceNumber = 0; $countSpaceLine > $spaceNumber; $spaceNumber++)
        {
            $details .= " ";
        }
    }
    echo "\033[32m$details\n\033[0m$status_bar\033[0m";
    flush();

    // when done, send a newline
    if($done == $total) {
        echo "\n\033[0m";
    }

}


class Timer {
    public $time;
    function __construct(){
        $this->start();
    }
    function start($offset=0){
        $this->time = microtime(true) + $offset;
    }
    function seconds(){
        return microtime(true) - $this->time;
    }
};


// We need this to limit the frequency of the progress bar. Or else it
// hugely slows down the app.
class FPSLimit {
    public $frequency;
    public $maxDt;
    public $timer;
    function __construct($freq){
        $this->setFrequency($freq);
        $this->timer = new Timer();
        $this->timer->start();
    }
    function setFrequency($freq){
        $this->frequency = $freq;
        $this->maxDt = 1.0/$freq;
    }
    function frame(){
        $dt = $this->timer->seconds();
        if($dt > $this->maxDt){
            $this->timer->start($dt - $this->maxDt);
            return true;
        }
        return false;
    }
};

class Progress {
    // generic progress class to update different things
    function update($units, $total){}
}

class SimpleProgress extends Progress {
    private $cols;
    private $limiter;
    private $units;
    private $total;
    private $className;

    function __construct(){
        // change the fps limit as needed
        $this->limiter = new FPSLimit(10);
        echo "\n";
    }

    function __destruct(){
        $this->draw();
    }

    function updateSize(){
        // get the number of columns
        $this->cols = exec("tput cols");
    }

    function draw(){
        $this->updateSize();
        show_status($this->units, $this->total, $this->cols, $this->cols, $this->className);
    }

    function update($units, $total, $className){
        $this->units = $units;
        $this->total = $total;
        $this->className = $className;
        if(!$this->limiter->frame())
            return;
        usleep(100000);
        $this->draw();
    }
}