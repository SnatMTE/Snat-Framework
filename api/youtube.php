<?php
/**
 * Snat's Framework - Snat's PHP framework of commonly used functions.
 * This part is for YouTube stuff.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License
*/

require_once 'vendor/autoload.php';

function snat_framework_youtube_upload_video($client, $title, $description, $tags, $category_id, $privacy_status, $video_path) {
    // Create a YouTube service object
    $youtube = new Google_Service_YouTube($client);

    // Set the video metadata
    $video = new Google_Service_YouTube_Video();
    $video->setSnippet(new Google_Service_YouTube_VideoSnippet([
        'title' => $title,
        'description' => $description,
        'tags' => $tags,
        'categoryId' => $category_id
    ]));
    $video->setStatus(new Google_Service_YouTube_VideoStatus([
        'privacyStatus' => $privacy_status
    ]));

    // Create a YouTube video insert request
    $insert_request = $youtube->videos->insert('status,snippet', $video);

    // Set the chunk size of the upload
    $chunk_size = 1 * 1024 * 1024;

    // Open the video file for reading
    $video_file = fopen($video_path, 'rb');

    // Create a MediaFileUpload object for the video file
    $media = new Google_Http_MediaFileUpload(
        $client,
        $insert_request,
        'video/*',
        null,
        true,
        $chunk_size
    );
    $media->setFileSize(filesize($video_path));

    // Upload the video file in chunks
    $status = false;
    $handle = $media->getResumeUri();
    while (!$status && !feof($video_file)) {
        $chunk = fread($video_file, $chunk_size);
        $status = $media->nextChunk($chunk);
    }

    // Close the video file handle
    fclose($video_file);

    // Return the uploaded video ID
    return $status['id'];
}

function snat_framework_youtube_search_videos($client, $query, $max_results = 10) {
    $youtube = new Google_Service_YouTube($client);

    // Define the parameters for the API request.
    $params = array(
        'q' => $query,
        'maxResults' => $max_results,
        'type' => 'video'
    );

    // Execute the search request.
    $searchResponse = $youtube->search->listSearch('id,snippet', $params);

    // Extract the video search results.
    $videos = array();
    foreach ($searchResponse->getItems() as $searchResult) {
        $video = array(
            'id' => $searchResult->id->videoId,
            'title' => $searchResult->snippet->title,
            'description' => $searchResult->snippet->description,
            'thumbnail' => $searchResult->snippet->thumbnails->default->url,
            'channel_title' => $searchResult->snippet->channelTitle
        );
        array_push($videos, $video);
    }

    return $videos;
}

function snat_framework_youtube_get_video_info($client, $video_id)
{
    $youtube = new Google_Service_YouTube($client);

    // Define the API request parameters.
    $params = [
        'id' => $video_id,
        'part' => 'snippet,statistics'
    ];

    // Send the API request to retrieve the video details.
    $video = $youtube->videos->listVideos($params)->getItems()[0];

    // Extract the relevant video details.
    $title = $video->getSnippet()->getTitle();
    $description = $video->getSnippet()->getDescription();
    $thumbnail_url = $video->getSnippet()->getThumbnails()->getHigh()->getUrl();
    $view_count = $video->getStatistics()->getViewCount();

    // Return the video details as an associative array.
    return [
        'title' => $title,
        'description' => $description,
        'thumbnail_url' => $thumbnail_url,
        'view_count' => $view_count
    ];
}

function snat_framework_youtube_get_channel_info($client, $channel_id) {
    try {
        // Build the YouTube Data API client
        $youtube = new Google_Service_YouTube($client);

        // Define the parts of the channel resource that we want to retrieve
        $part = 'snippet,statistics';

        // Call the YouTube Data API's channels.list method to retrieve the channel information
        $channelsResponse = $youtube->channels->listChannels(
            $part,
            array('id' => $channel_id)
        );

        // Get the first channel in the response
        $channel = $channelsResponse[0];

        // Extract the relevant information from the channel object
        $channelInfo = array(
            'channel_id' => $channel['id'],
            'title' => $channel['snippet']['title'],
            'description' => $channel['snippet']['description'],
            'thumbnail_url' => $channel['snippet']['thumbnails']['default']['url'],
            'subscriber_count' => $channel['statistics']['subscriberCount'],
            'view_count' => $channel['statistics']['viewCount'],
            'video_count' => $channel['statistics']['videoCount']
        );

        return $channelInfo;

    } catch (Exception $e) {
        // Throw an exception if an error occurs
        throw new Exception('Error retrieving YouTube channel information: ' . $e->getMessage());
    }
}

function snat_framework_youtube_create_playlist($client, $title, $description, $privacy_status) {
    // Define the YouTube service object
    $youtube = new Google_Service_YouTube($client);
  
    // Define the playlist object
    $playlist = new Google_Service_YouTube_Playlist();
  
    // Set the title and description of the playlist
    $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
    $playlistSnippet->setTitle($title);
    $playlistSnippet->setDescription($description);
    $playlist->setSnippet($playlistSnippet);
  
    // Set the privacy status of the playlist
    $status = new Google_Service_YouTube_PrivacyStatus();
    $status->setPrivacyStatus($privacy_status);
    $playlist->setStatus($status);
  
    // Create the playlist
    $playlistResponse = $youtube->playlists->insert('snippet,status', $playlist, array());
    return $playlistResponse->id;
  }
  
function snat_framework_youtube_add_video_to_playlist($client, $video_id, $playlist_id) {
    $youtube = new Google_Service_YouTube($client);
  
    // Create a resource for the new playlist item.
    $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
    $playlistItemSnippet->setPlaylistId($playlist_id);
    $playlistItemSnippet->setResourceId(new Google_Service_YouTube_ResourceId([
      'kind' => 'youtube#video',
      'videoId' => $video_id
    ]));
  
    // Create the playlist item request.
    $playlistItem = new Google_Service_YouTube_PlaylistItem();
    $playlistItem->setSnippet($playlistItemSnippet);
  
    // Call the YouTube Data API's playlistItems.insert method to add the video to the playlist.
    $playlistItemResponse = $youtube->playlistItems->insert('snippet', $playlistItem);
  
    // Return the playlist item resource that was created.
    return $playlistItemResponse;
  }
  
?>