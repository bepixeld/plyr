<?php class rex_var_for_video extends rex_var 
{
   protected function getOutput() 
   {
	$id = $this->getArg('id', 0, true);
        if (!in_array($this->getContext(), ['module', 'action']) || !is_numeric($id) || $id < 1 || $id > 20) {
            return false;
        }
    $value = $this->getContextData()->getValue('value' . $id);
        
		//Variablen
		$localAutoplay = $out = "";
		$url = $value;
		// GET INPUT LINK AND CHECK IS IT URL/MEDIA
		$plyr = new rex_video();
		$link = $plyr->getVideoType($url);
		// GET STANDARD CONFIG VARIABLES
		$autoplayStandard = rex_config::get('video', 'autoplay_plyr');
		$hideControls = rex_config::get('video', 'controls_plyr');
		$clickToPlay = rex_config::get('video', 'click_plyr');
		//$autoplay_audio = rex_config::get('video', 'autoplay_audio');
		$loop_audio = rex_config::get('video', 'loop_audio');
		
		if($plyr->checkYoutube($link) == true) {
			$out = '<div class="rex_video '.$autoplayStandard.' '.$hideControls.' '.$clickToPlay.'" data-type="youtube"  data-video-id="'.$plyr->getYoutubeId($link).'"></div></br>';
		}
		if($plyr->checkVimeo($link) == true) {
			$out =  '<div class="rex_video '.$hideControls.' '.$autoplayStandard.' '.$clickToPlay.'" data-type="vimeo" data-video-id="'.$plyr->getVimeoId($link).'"></div></br>';
		}
		if($plyr->checkMedia($url) !== false) {
			if($autoplayStandard == 'Ja') {
				$localAutoplay = "autoplay";
			}
			dump("TEST");
			
			$out =  '
				<section>
					<video '.$localAutoplay.' volume=1>
						<source src="'.$link.'" type="video/mp4">
					</video>
				</section>
				';
			
		}
		if($plyr->checkAudio($url) !== false) {
			$out =   '
				<section>
					<audio '.$loop_audio.'>
						<source src="'.$link.'" type="audio/mp3">
					</audio>
				</section>
				';
		}
	
	// Reine Textausgaben müssen mit 'self::quote()' als String maskiert werden.
	return self::quote($out);
   }
}
