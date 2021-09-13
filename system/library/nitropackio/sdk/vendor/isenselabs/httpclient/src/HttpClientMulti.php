<?php

namespace NitroPack;

class HttpClientMulti {
    private $clients;
    private $successCallback;
    private $errorCallback;

    public function __construct() {
        $this->clients = array();
        $this->successCallback = NULL;
        $this->errorCallback = NULL;
    }

    public function push($client) {
        $this->clients[] = $client;
    }

    public function getClients() {
        return $this->clients;
    }

    public function onSuccess($callback) {
        $this->successCallback = $callback;
    }

    public function onError($callback) {
        $this->errorCallback = $callback;
    }

    public function fetchAll($follow_redirects = true, $method = "GET") {
        foreach ($this->clients as $client) {
            $client->fetch($follow_redirects, $method, true);
        }

        return $this->readAll();
    }

    /* Returns an array with succeeded and failed clients
     * [
     *     [succeeded clients...],
     *     [[failed client, exception]...]
     * ]
     */
    public function readAll() {
        $succeededClients = [];
        $failedClients = [];

        while ($this->clients) {
            // Check whether to sleep using a syscall in order to conserve CPU usage
            $write = $except = NULL;
            $read = [];
            $remainingTimeouts = [];
            $canSleep = true;

            foreach ($this->clients as $client) {
                if (!$client->wasEmptyRead() || ($client->getState() != HttpClientState::DOWNLOAD && $client->getState() != HttpClientState::READY)) {
                    $canSleep = false;
                    break;
                }

                $read[] = $client->sock;
                $remainingTimeouts[] = $client->timeout - (microtime(true) - $client->last_read);
            }

            if ($canSleep) {
                $microtimeout = (int)(min($remainingTimeouts) * 1000000);
                if ($microtimeout > 0) {
                    stream_select($read, $write, $except, 0, $microtimeout);
                }
            }
            // End check

            foreach ($this->clients as $client) {
                try {
                    if ($client->asyncLoop()) {
                        $this->removeClient($client);
                        $succeededClients[] = $client;
                        if ($this->successCallback) {
                            call_user_func($this->successCallback, $client);
                        }
                    }
                } catch (\Exception $e) {
                    $this->removeClient($client);
                    $failedClients[] = [$client, $e];
                    if ($this->errorCallback) {
                        call_user_func($this->errorCallback, $client, $e);
                    }
                }
            }
        }

        return [$succeededClients, $failedClients];
    }

    private function removeClient($client) {
        $index = array_search($client, $this->clients, true);
        array_splice($this->clients, $index, 1);
    }
}
