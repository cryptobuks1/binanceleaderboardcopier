 function backgroundfeed(mode) {
										 
           
         url =  cordova.file.applicationDirectory + 'www/media/coin.mp3';
		 
		  bgaudiobackgroundfeed = new Media( url, onSuccess2, onError2, onStatus2);
		   
		   
		   bgaudiobackgroundfeed.play();
		     }
			 
			  function foefeed(mode) {
										 
           
         url =  cordova.file.applicationDirectory + 'www/media/ghost.mp3';
		 
		  bgaudiofoe = new Media( url, onSuccessdf, onErrordf, onStatusdf);
		   
		   
		   bgaudiofoe.play();
		     }
			 
			 
			  function backgroundfeed2(mode) {
										 
           
         url =  cordova.file.applicationDirectory + 'www/media/pop.wav';
		 
		  bgaudiobackgroundfeed2 = new Media( url, onSuccessb, onErrorb, onStatusb);
		   
		   
		   bgaudiobackgroundfeed2.play();
		     }
				
					  	  	 		      function backgroundmusic(mode) {
										 
           
         url =  cordova.file.applicationDirectory + 'www/media/main_bgm.mp3';
		 
		  bgaudiobackmusic = new Media( url, onSuccess, onError, onStatus);
		   bgaudiobackmusic.play();
		     }
			 
			 					  	  	 		      function backgroundmusic2(mode) {
										 
           
         url =  cordova.file.applicationDirectory + 'www/media/water_bonus.mp3';
		 
		  bgaudiobackmusic2 = new Media( url, onSuccess2, onError2, onStatus2);
		   bgaudiobackmusic2.play();
		     }
			 
			 
			  function onSuccess2() {
    }
    
    function onError2(error) {
	//console.log(error.code);
 
    }
    
    function onStatus2(status) {
        if( status==Media.MEDIA_STOPPED ) {
           bgaudiobackmusic2.play();
        }
    }
	
	
			 
			     function onSuccess() {
    }
    
    function onError(error) {
	//console.log(error.code);
 
    }
    
    function onStatus(status) {
        if( status==Media.MEDIA_STOPPED ) {
           bgaudiobackmusic.play();
        }
    }
	
	
	  function onSuccess2() {
	            bgaudiobackgroundfeed2.stop();
bgaudiobackgroundfeed2.release();
    }
    
    function onError2(error) {
 
    }
    
    function onStatus2(status) {
        if( status==Media.MEDIA_STOPPED ) {
          bgaudiobackgroundfeed2.stop();
bgaudiobackgroundfeed2.release();
        }
    }
	
	
	
	
	
		  function onSuccessdf() {
	            bgaudiofoe.stop();
bgaudiofoe.release();
    }
    
    function onErrordf(error) {
 
    }
    
    function onStatusdf(status) {
        if( status==Media.MEDIA_STOPPED ) {
          bgaudiofoe.stop();
bgaudiofoe.release();
        }
    }
	
	
	
	
	
	
		  function onSuccessb() {
	            bgaudiobackgroundfeed2.stop();
bgaudiobackgroundfeed2.release();
    }
    
    function onErrorb(error) {
 
    }
    
    function onStatusb(status) {
        if( status==Media.MEDIA_STOPPED ) {
          bgaudiobackgroundfeed2.stop();
bgaudiobackgroundfeed2.release();
        }
    }
	
	
	
						  	  	 		      function yuppi(mode) {
										 
           
         url =  cordova.file.applicationDirectory + 'www/media/yuppi.wav';
		 
		  bgaudioyuppi = new Media( url, onSuccessyuppi, onErroryuppi, onStatusyuppi);
		   bgaudioyuppi.play();
		     }
			 
			 
			 
		  function onSuccessyuppi() {
	            bgaudioyuppi.stop();
bgaudioyuppi.release();
    }
    
    function onErroryuppi(error) {
 
    }
    
    function onStatusyuppi(status) {
        if( status==Media.MEDIA_STOPPED ) {
          bgaudioyuppi.stop();
bgaudioyuppi.release();
        }
    }
	
	
	
	
	
	
	
	   function game_over(mode) {
										 
           
         url =  cordova.file.applicationDirectory + 'www/media/gameover.mp3';
		 
		  bggameover = new Media( url, onSuccessgameover, onErrorgameover, onStatusgameover);
		   bggameover.play();
		     }
			 
			 
			 
		  function onSuccessgameover() {
	            bggameover.stop();
bggameover.release();
    }
    
    function onErrorgameover(error) {
 
    }
    
    function onStatusgameover(status) {
        if( status==Media.MEDIA_STOPPED ) {
          bggameover.stop();
bggameover.release();
        }
    }
	
	 
	