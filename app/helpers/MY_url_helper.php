<?php

// URL extension, helper that allow easy use of CDN
/*
 * I hereby grant DemoChimp LLC a worldwide perpetual license, 
 * free of any ongoing fees, to use, modify, and or distribute 
 * as part of its software these code pieces and files.
 */
function cdn_url($uri)
{
   $CI =& get_instance();
   $cnf = $CI->config->item('cdn_urls');
   $ext = pathinfo($uri, PATHINFO_EXTENSION);
   $url = $cnf[$ext].$uri;
   return $url;
}

function cdn_base($uri)
{
   $CI =& get_instance();
   $cnf = $CI->config->item('cdn_urls');
   $url = $cnf["base"].$uri;
   return $url;
}