<?php

namespace PhilKra\Events;

use PhilKra\Helper\Timer;

/**
 *
 * Abstract Transaction class for all inheriting Transactions
 *
 * @link https://www.elastic.co/guide/en/apm/server/master/transaction-api.html
 *
 */
class Transaction extends EventBean implements \JsonSerializable
{
    /**
     * Transaction Name
     *
     * @var string
     */
    private $name;

    /**
     * Transaction Timer
     *
     * @var \PhilKra\Helper\Timer
     */
    private $timer;

    /**
     * Summary of this Transaction
     *
     * @var array
     */
    private $summary = [
        'duration'  => 0.0,
        'backtrace' => null,
        'headers'   => []
    ];

    /**
     * The spans for the transaction
     *
     * @var array
     */
    private $spans = [];

    /**
     * The request body for the transaction
     *
     * @var string
     */
    private $body;

    /**
    * Create the Transaction
    *
    * @param string $name
    * @param array $contexts
    */
    public function __construct($name, array $contexts)
    {
        parent::__construct($contexts);
        $this->setTransactionName($name);
        $this->timer = new Timer();
    }

    /**
    * Start the Transaction
    *
    * @return void
    */
    public function start()
    {
        $this->timer->start();
    }

    /**
     * Starting Timestamp
     *
     * @return double
     */
    public function getStartedOn()
    {
        return $this->timer->getStartedOn();
    }

    /**
     * Stop the Transaction
     *
     * @param integer|null $duration
     *
     * @return void
     */
    public function stop($duration = null)
    {
        // Stop the Timer
        $this->timer->stop();

        // Store Summary
        $this->summary['duration']  = $duration ?: round($this->timer->getDuration(), 3);
        $this->summary['headers']   = (function_exists('xdebug_get_headers') === true) ? xdebug_get_headers() : [];
        $this->summary['backtrace'] = debug_backtrace();
    }

    /**
    * Set the Transaction Name
    *
    * @param string $name
    *
    * @return void
    */
    public function setTransactionName($name)
    {
        $this->name = $name;
    }

    /**
    * Get the Transaction Name
    *
    * @return string
    */
    public function getTransactionName()
    {
        return $this->name;
    }

    /**
    * Get the Summary of this Transaction
    *
    * @return array
    */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set the spans for the transacton
     *
     * @param array $spans
     *
     * @return void
     */
    public function setSpans(array $spans)
    {
        $this->spans = $spans;
    }

    /**
     * Get the spans from the transaction
     *
     * @return array
     */
    private function getSpans()
    {
        return $this->spans;
    }

    /**
     * Set the Body for the transacton
     *
     * @param string $body
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get the Body from the transaction
     *
     * @return array
     */
    private function getBody()
    {
        return $this->body;
    }

    /**
    * Serialize Transaction Event
    *
    * @return array
    */
    public function jsonSerialize()
    {
        $data = [
            'id'        => $this->getId(),
            'timestamp' => $this->getTimestamp(),
            'name'      => $this->getTransactionName(),
            'duration'  => $this->summary['duration'],
            'type'      => $this->getMetaType(),
            'result'    => $this->getMetaResult(),
            'context'   => $this->getContext(),
            'spans'     => $this->getSpans(),
            'processor' => [
                'event' => 'transaction',
                'name'  => 'transaction',
            ]
        ];

        $body = $this->getBody();

        if ($body !== null) {
            $data['context']['request']['body'] = $body;
        }

        return $data;
    }
}
