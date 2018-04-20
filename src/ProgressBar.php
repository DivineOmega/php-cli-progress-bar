<?php

namespace DivineOmega\CliProgressBar;

use Khill\Duration\Duration;

class ProgressBar
{
    private $progress = 0;
    private $maxProgress = 100;
    private $message = 'Working...';
    private $barWidth = 20;

    private $startTime = 0;

    private $lastProgressAdvancement;
    private $advancementTimings = [];
    private $maxAdvancementTimings = 50;

    public function __construct()
    {
        $this->startTime = time();
        $this->lastProgressAdvancement = microtime(true);
    }

    public function setProgress($progress) 
    {
        $this->progress = $progress;
        return $this;
    }

    public function setMaxProgress($maxProgress) 
    {
        if ($maxProgress <= 0) {
            throw new \Exception('Max progress can not be zero or below.');
        }

        $this->maxProgress = $maxProgress;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setBarWidth($barWidth) {
        $this->barWidth = $barWidth;
        return $this;
    }

    public function advance()
    {
        $now = microtime(true);
        $this->advancementTimings[] = $now - $this->lastProgressAdvancement;
        $this->lastProgressAdvancement = $now;

        if (count($this->advancementTimings) > $this->maxAdvancementTimings) {
            array_shift($this->advancementTimings);
        }

        $this->progress++;
        return $this;
    }

    private function getPercentage()
    {
        return number_format(($this->progress / $this->maxProgress) * 100, 1, '.', '');
    }

    private function getTimeElapsed()
    {
        return time() - $this->startTime;
    }

    private function getHumanReadableTimeElapsed()
    {
        return $this->getTimeElapsed() >= 1 ? (new Duration($this->getTimeElapsed()))->humanize() : '-';
    }

    private function getTimeRemaining()
    {
        if (count($this->advancementTimings) == 0) {
            return 0;
        }

        $averageAdvancementTiming = array_sum($this->advancementTimings)/count($this->advancementTimings);

        return $averageAdvancementTiming * ($this->maxProgress - $this->progress);
    }

    private function getHumanReadableTimeRemaining()
    {
        return $this->getTimeRemaining() >= 1 ? (new Duration($this->getTimeRemaining()))->humanize() : '-';
    }

    public function display()
    {
        echo Constants::CARRIAGE_RETURN_CHARACTER;

        echo $this->message;
        echo '   ';

        $percentage = $this->getPercentage();

        echo $percentage.'%';
        echo '   ';

        echo $this->progress.'/'.$this->maxProgress;
        echo '   ';

        echo 'ETC: ';
        echo $this->getHumanReadableTimeRemaining();
        echo '   ';

        echo 'Elapsed: ';
        echo $this->getHumanReadableTimeElapsed();
        echo '   ';

        $barCompleteWidth = ceil($this->barWidth * $percentage/100);
        $barIncompleteWidth = floor($this->barWidth * (100-$percentage)/100);

        if ($barCompleteWidth > $this->barWidth) {
            $barCompleteWidth = $this->barWidth;
        }

        if ($barIncompleteWidth < 0) {
            $barIncompleteWidth = 0;
        }

        if($barCompleteWidth + $barIncompleteWidth > $this->barWidth) {
            $barIncompleteWidth -= $this->barWidth - $barCompleteWidth;
        }
        
        echo str_repeat(Constants::PROGRESS_COMPLETE_CHARACTER, $barCompleteWidth);
        echo str_repeat(Constants::PROGRESS_INCOMPLETE_CHARACTER, $barIncompleteWidth);
        
        echo '   ';
    }

    public function complete()
    {
        $this->setProgress($this->maxProgress)->display();
        
        echo Constants::NEW_LINE_CHARACTER;
    }
}