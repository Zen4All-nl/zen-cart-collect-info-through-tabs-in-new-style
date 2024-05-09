<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

/**
 * 
 * @param string $repository <p>Repository name</p>
 * @return string <p>Latest version of a module</p>
 */
function getLatestVersion(string $repository): string
{
  $url = 'https://api.github.com/repos/Zen4All-nl/' . $repository . '/git/refs/tags';
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, "test");
  $r = curl_exec($ch);
  curl_close($ch);

  $response_array = json_decode($r, true);
  $lastInstantInArray = end($response_array);
  $latestVersion = preg_replace('/refs\/tags\//', '', $lastInstantInArray['ref']);
  return $latestVersion;
}

/**
 * 
 * @param string $repository <p>Name of the GitHub repository</p>
 * @param string $currentVersion <p>The current module version</p>
 * @return boolean
 */
function updatAvailable(string $repository, string $currentVersion): bool
{

  $latestVersion = getLatestVersion($repository, $currentVersion);
  $update = 'false';
  if (version_compare($latestVersion, $currentVersion) == 1) {
    $update = 'true';
  }
  return $update;
}
