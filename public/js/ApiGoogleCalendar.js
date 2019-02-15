	  var date = new Date(); 
	  //alert(date);
	  var date2 = date;

	  date2.setDate(date.getDate()+2);
	  var i;
	  var tableEvent = [];
	  //alert(date2);
// Client ID and API key from the Developer Console
      var CLIENT_ID = '516094392963-ls2uqh5mkl1n42itdrtjkp6fo34n5en9.apps.googleusercontent.com';
      var API_KEY = 'AIzaSyA556nQpdim8unX3vlxTnpzLA-RrAiqQGY';

      // Array of API discovery doc URLs for APIs used by the quickstart
      var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];

      // Authorization scopes required by the API; multiple scopes can be
      // included, separated by spaces.
      var SCOPES = "https://www.googleapis.com/auth/calendar.readonly";

      var authorizeButton = document.getElementById('authorize_button');
      var signoutButton = document.getElementById('signout_button');

      /**
       *  On load, called to load the auth2 library and API client library.
       */
      function handleClientLoad() {
        gapi.load('client:auth2', initClient);
      }

      /**
       *  Initializes the API client library and sets up sign-in state
       *  listeners.
       */
      function initClient() {
        gapi.client.init({
          apiKey: API_KEY,
          clientId: CLIENT_ID,
          discoveryDocs: DISCOVERY_DOCS,
          scope: SCOPES
        }).then(function () {
          // Listen for sign-in state changes.
          gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);

          // Handle the initial sign-in state.
          updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
          authorizeButton.onclick = handleAuthClick;
          signoutButton.onclick = handleSignoutClick;
        }, function(error) {
          appendPre(JSON.stringify(error, null, 2));
        });
      }

      /**
       *  Called when the signed in status changes, to update the UI
       *  appropriately. After a sign-in, the API is called.
       */
      function updateSigninStatus(isSignedIn) {
        if (isSignedIn) {
          authorizeButton.style.display = 'none';
          signoutButton.style.display = 'none'/*'block'*/;
          listUpcomingEvents();
        } else {
          authorizeButton.style.display = 'block';
          signoutButton.style.display = 'none';
        }
      }

      /**
       *  Sign in the user upon button click.
       */
      function handleAuthClick(event) {
        gapi.auth2.getAuthInstance().signIn();
      }

      /**
       *  Sign out the user upon button click.
       */
      function handleSignoutClick(event) {
        gapi.auth2.getAuthInstance().signOut();
      }

      /**
       * Append a pre element to the body containing the given message
       * as its text node. Used to display the results of the API call.
       *
       * @param {string} message Text to be placed in pre element.
       */
      function appendPre(message) {
	        var pre = document.getElementById('content');
	        var textContent = document.createTextNode(message + '\n');
	        pre.appendChild(textContent);
		
      }

      /**
       * Print the summary and start datetime/date of the next ten events in
       * the authorized user's calendar. If no events are found an
       * appropriate message is printed.
       */
      function listUpcomingEvents() {
        gapi.client.calendar.events.list({
          'calendarId': 'primary',
          'timeMin': (new Date()).toISOString(),
          'showDeleted': false,
          'singleEvents': true,
          'maxResults': 10,
          'orderBy': 'startTime'
        }).then(function(response) {
          var events = response.result.items;
          appendPre('Upcoming events:');

          if (events.length > 0) {
            for (i = 0; i < events.length; i++) {
              var event = events[i];
              var when = event.start.dateTime;
              if (!when) {
                when = event.start.date;
              }
              //alert((event.start.dateTime));
              //alert(date2.toJSON())//toLocaleDateString());
              //alert(event.start.dateTime < date2.toJSON());
              if(event.start.dateTime < date2.toJSON())
        	  {
            	  
            	  //appendPre(event.summary + ' (' + when + ')');
            	  tableEvent[i] = (event.summary + ' ('+ when +')');
            	  //alert(tableEvent[i]);
        	  }
            }
          } else {
            appendPre('No upcoming events found.');
          }
        });
      }
      
      
	function AffichagetextEvent(text)
	{
		document.getElementById('Events').innerHTML = text;
		//document.getElementById('btn').remove();
	}

	
	function AffichageDynEvent()
	{
		//i=4 ?  0 : i++;
		if(i>= tableEvent.length )
		{
			i=0;
		}
		AffichagetextEvent(tableEvent[i] + i);		
		i++;
	}
	  
	function Clear()
	{
		document.getElementById('Dynamique-txt').innerHTML = '';
		document.getElementById('Dynamique-img').innerHTML = '';
	}
      