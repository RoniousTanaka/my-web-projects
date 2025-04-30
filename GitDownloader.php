<?php

class GitDownloader
{
    private $apiUrl = "https://api.github.com/repos/";
    private $owner;
    private $repo;
    private $branch;

    public function __construct($owner, $repo, $branch = "main")
    {
        $this->owner = $owner;
        $this->repo = $repo;
        $this->branch = $branch;
    }

    public function downloadFile($filePath, $destination)
    {
        $url = $this->apiUrl . "{$this->owner}/{$this->repo}/contents/{$filePath}?ref={$this->branch}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "GitDownloader");

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['content'])) {
            $fileContent = base64_decode($data['content']);
            file_put_contents($destination, $fileContent);
            echo "File downloaded to $destination\n";
        } else {
            echo "Failed to download file: {$data['message']}\n";
        }
    }
}

// Usage example
$owner = "octocat";
$repo = "Hello-World";
$filePath = "README.md";
$destination = "README.md";

$downloader = new GitDownloader($owner, $repo);
$downloader->downloadFile($filePath, $destination);

?>
