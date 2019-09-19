<?php

namespace PhilKra\Helper;

use PhilKra\Exception\Timer\NotStartedException;
use PhilKra\Exception\Timer\NotStoppedException;

/**
 * Timer for Duration tracing
 */
class Timer
{
    /**
     * Starting Timestamp
     *
     * @var double
     */
    private $startedOn = null;

    /**
     * Ending Timestamp
     *
     * @var double
     */
    private $stoppedOn = null;

    /**
     * Start the Timer
     *
     * @return void
     */
    public function start()
    {
        $this->startedOn = microtime(true);
    }

    /**
     * Stop the Timer
     *
     * @throws \PhilKra\Exception\Timer\NotStartedException
     *
     * @return void
     */
    public function stop()
    {
        if ($this->startedOn === null) {
            throw new NotStartedException();
        }

        $this->stoppedOn = microtime(true);
    }

    /**
     * Get the elapsed Duration of this Timer
     *
     * @throws \PhilKra\Exception\Timer\NotStoppedException
     *
     * @return float
     */
    public function getDuration()
    {
        if ($this->stoppedOn === null) {
            throw new NotStoppedException();
        }

        return $this->toMilli($this->stoppedOn - $this->startedOn);
    }

    /**
     * Get the current elapsed Interval of the Timer
     *
     * @throws \PhilKra\Exception\Timer\NotStartedException
     *
     * @return float
     */
    public function getElapsed()
    {
        if ($this->startedOn === null) {
            throw new NotStartedException();
        }

        return ($this->stoppedOn === null) ?
            $this->toMilli(microtime(true) - $this->startedOn) :
            $this->getDuration();
    }

    /**
     * Convert the Duration from Seconds to Milli-Seconds
     *
     * @param  float $num
     *
     * @return float
     */
    private function toMilli($num)
    {
        return $num * 1000;
    }

    /**
     * Starting Timestamp
     *
     * @return double
     */
    public function getStartedOn()
    {
        if ($this->startedOn === null) {
            throw new NotStartedException();
        }

        return $this->startedOn;
    }
}
