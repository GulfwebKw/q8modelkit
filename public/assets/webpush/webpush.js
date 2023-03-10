
  var WEB_URL='';

  var firebaseConfig = {
    apiKey: "AIzaSyBWnjUyiomgggIv2Yip1N9u4q3hNk3-9J8",
    authDomain: "gullfweb.firebaseapp.com",
    databaseURL: "https://gullfweb.firebaseio.com",
    projectId: "gullfweb",
    storageBucket: "gullfweb.appspot.com",
    messagingSenderId: "107251807318",
    appId: "1:107251807318:web:7295ba1d22e203fcbe77f7"
  };

  // Initialize Firebase

  firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function() {
                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {

				console.log("Token is : "+token);

				//console.log("Token is : "+token);

				if(token!=""){
				$.ajax({
						type: "GET",
						url: WEB_URL+"/web_push_token_save",
						data: "token="+token,
						dataType: "json",
			            contentType: false,
    	                cache: false,
			            processData:false,
						success: function(msg){ 
						console.log(msg.status);
						},
						error: function(msg){
						//alert('error-found');
						console.log(msg.status);
						}

					});	

				}

            })

            .catch(function(err) {

                //ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err

               // console.log("Unable to get permission to notify.", err);

            });



        messaging.onMessage(function(payload) {

            //console.log("Message received. ", payload);

            //NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload);

            //kenng - foreground notifications

			//alert(JSON.stringify(payload.notification));

            const {title,options} = JSON.stringify(payload.notification);

            navigator.serviceWorker.ready.then(registration => {

                registration.showNotification(title, options);

            });

        });


  