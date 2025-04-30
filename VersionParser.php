<?php

class CurlFactory
{
    public static function create($url, $options = [])
    {
        // Initialize a cURL session
        $ch = curl_init($url);

        // Default options
        $defaultOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_USERAGENT      => "CurlFactory/1.0",
        ];

        // Merge custom options with default options
        $finalOptions = $options + $defaultOptions;

        // Set cURL options
        curl_setopt_array($ch, $finalOptions);

        return $ch;
    }

    public static function execute($ch)
    {
        // Execute the cURL session
        $response = curl_exec($ch);
        $error = curl_error($ch);

        if ($error) {
            throw new Exception("cURL Error: $error");
        }

        return $response;
    }

    public static function close($ch)
    {
        // Close the cURL session
        curl_close($ch);
    }
}

// Example Usage
try {
    $url = "https://api.github.com";
    $ch = CurlFactory::create($url);

    $response = CurlFactory::execute($ch);
    echo "Response:\n$response\n";

    CurlFactory::close($ch);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>
