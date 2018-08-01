<?php
namespace Kruzya\Telegram\BbCode\Helper;
// Kruzya/Telegram/BbCode/Helper

class Telegram {
  public static function matchCallback($url, $matchedId, \XF\Entity\BbCodeMediaSite $site, $siteId) {
    if (preg_match('#t\.me\/(?P<channel>.{1,})\/(?P<postId>.{1,})#si', $url, $matches))
      $matchedId = $matches['channel'] . '/' . $matches['postId'];
    return $matchedId;
  }
}
