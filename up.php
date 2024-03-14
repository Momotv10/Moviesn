<?php

$username = 'Momotv10';
$repository = 'Moviesn';
$github_pat = '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';

function updateM3UFile($username, $repository, $github_pat) {
    $m3u_url = 'http://ghostiptv.vip/get.php?username=6257225&password=4161462&type=m3u_plus';
    $m3u_content = file_get_contents($m3u_url);

    if ($m3u_content === false) {
        die("Unable to download M3U file.");
    }

    $file_name = 'ggg.m3u';

    $repository_url = "https://github.com/$username/$repository";
    $access_token = "token $github_pat";
    $file_url = "https://api.github.com/repos/$username/$repository/contents/$file_name";

    // Get the current contents of the file
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $file_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: $access_token",
        "User-Agent: PHP"
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        die("Unable to access the file on GitHub.");
    }

    $file_data = json_decode($response, true);
    $current_content = base64_decode($file_data['content']);

    // Check if the content has changed
    if ($current_content === $m3u_content) {
        die("The file content has not changed.");
    }

    // Upload the new content
    $file_data = array(
        'message' => 'Update M3U file',
        'content' => base64_encode($m3u_content),
        'sha' => $file_data['sha']
    );

    $post_data = json_encode($file_data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $file_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: $access_token",
        "User-Agent: PHP"
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        die("Unable to update the file on GitHub.");
    }

    $raw_url = "https://raw.githubusercontent.com/$username/$repository/master/$file_name";

    echo "M3U file updated successfully. You can access it here: $raw_url";
}

updateM3UFile($username, $repository, $github_pat);

?>
