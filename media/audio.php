<?php
/**
 * Snat's Framework - Snat's PHP framework of commonly used functions.
 * This part is for all audio related functions.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License

 * A lot of this was written for Amazing World Music - https://amazingworldmusic.com/
*/ 

function snat_framework_get_duration($file_path) {
  // Get the duration of an audio file in seconds
  $duration = false;
  if (extension_loaded('id3')) {
    $id3 = id3_get_tag($file_path);
    if (isset($id3['Length'])) {
      $duration = $id3['Length'];
    }
  } elseif (extension_loaded('ffmpeg')) {
    $ffprobe = \FFMpeg\FFProbe::create();
    $format = $ffprobe->format($file_path);
    $duration = $format->get('duration');
  } else {
    throw new Exception('No audio library found');
  }
  return $duration;
}

function snat_framework_convert_audio($input_path, $output_path, $format) {
  // Convert an audio file to a different format
  $command = "ffmpeg -i {$input_path} -vn -acodec {$format} {$output_path} 2>&1";
  $output = shell_exec($command);
  return $output;
}

function snat_framework_get_waveform_data($file_path, $width, $height) {
  // Generate waveform data for an audio file
  $waveform = new Waveform\Waveform($file_path, $width, $height);
  $waveform_data = $waveform->render();
  return $waveform_data;
}

function snat_framework_normalize_audio($file_path, $output_path) {
    // Normalize the volume of an audio file using SoX
    $command = "sox {$file_path} {$output_path} --norm=-3 2>&1";
    $output = shell_exec($command);
    return $output;
  }
  
  function snat_framework_get_audio_metadata($file_path) {
    // Get metadata for an audio file using the getID3 library
    $getID3 = new getID3();
    $file_info = $getID3->analyze($file_path);
    $metadata = array(
      'artist' => isset($file_info['tags']['id3v2']['artist']) ? implode(', ', $file_info['tags']['id3v2']['artist']) : '',
      'title' => isset($file_info['tags']['id3v2']['title']) ? implode(', ', $file_info['tags']['id3v2']['title']) : '',
      'album' => isset($file_info['tags']['id3v2']['album']) ? implode(', ', $file_info['tags']['id3v2']['album']) : '',
      'year' => isset($file_info['tags']['id3v2']['year']) ? implode(', ', $file_info['tags']['id3v2']['year']) : '',
      'genre' => isset($file_info['tags']['id3v2']['genre']) ? implode(', ', $file_info['tags']['id3v2']['genre']) : '',
      'duration' => isset($file_info['playtime_seconds']) ? $file_info['playtime_seconds'] : '',
    );
    return $metadata;
  }
  
  function snat_framework_get_audio_analysis($file_path) {
    // Get audio analysis data for an audio file using the Music-Analysis library
    $analysis = new MusicAnalysis($file_path);
    $analysis_data = $analysis->getAnalysisData();
    return $analysis_data;
  }

  unction snat_framework_convert_audio($input_file_path, $output_file_path, $output_format = 'mp3') {
    // Convert an audio file from one format to another using FFmpeg
    $command = "ffmpeg -i {$input_file_path} -q:a 0 -map a {$output_file_path}.{$output_format} 2>&1";
    $output = shell_exec($command);
    return $output;
  }
  
  function snat_framework_trim_audio($input_file_path, $output_file_path, $start_time, $end_time) {
    // Trim an audio file to a specified start and end time using FFmpeg
    $command = "ffmpeg -i {$input_file_path} -ss {$start_time} -to {$end_time} -c copy {$output_file_path} 2>&1";
    $output = shell_exec($command);
    return $output;
  }
  
  function snat_framework_mix_audio($input_files, $output_file_path) {
    // Mix multiple audio files into a single file using FFmpeg
    $inputs = '';
    foreach ($input_files as $file_path) {
      $inputs .= "-i {$file_path} ";
    }
    $command = "ffmpeg {$inputs} -filter_complex amix=inputs=" . count($input_files) . ":duration=first:dropout_transition=2 {$output_file_path} 2>&1";
    $output = shell_exec($command);
    return $output;
  }

?>
