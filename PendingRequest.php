<?php

class PendingRequest
{
    private $requests = [];

    public function addRequest($requestId, $requestData)
    {
        $this->requests[$requestId] = [
            'data' => $requestData,
            'status' => 'pending',
            'timestamp' => time()
        ];
        echo "Request $requestId added successfully.\n";
    }

    public function getRequest($requestId)
    {
        if (isset($this->requests[$requestId])) {
            return $this->requests[$requestId];
        }
        return "Request $requestId not found.";
    }

    public function updateRequestStatus($requestId, $newStatus)
    {
        if (isset($this->requests[$requestId])) {
            $this->requests[$requestId]['status'] = $newStatus;
            echo "Request $requestId status updated to $newStatus.\n";
        } else {
            echo "Request $requestId not found.\n";
        }
    }

    public function listPendingRequests()
    {
        foreach ($this->requests as $id => $request) {
            if ($request['status'] === 'pending') {
                echo "Request ID: $id | Data: " . json_encode($request['data']) . " | Timestamp: " . date("Y-m-d H:i:s", $request['timestamp']) . "\n";
            }
        }
    }
}

// Example usage:
$pendingRequest = new PendingRequest();
$pendingRequest->addRequest(101, ['user' => 'Alice', 'type' => 'Approval']);
$pendingRequest->addRequest(102, ['user' => 'Bob', 'type' => 'Review']);

$pendingRequest->updateRequestStatus(102, 'completed');

echo "Pending Requests:\n";
$pendingRequest->listPendingRequests();

?>
