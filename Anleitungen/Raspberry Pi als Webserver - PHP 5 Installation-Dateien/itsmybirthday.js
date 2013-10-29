var itsmybirthday = {
	init: function()
	{
	},
		
	addWishes: function(pid, tid)
	{
		if(use_xmlhttprequest == 1 && imb_wishesEnabled == 1)
		{
			this.spinner = new ActivityIndicator("body", {image: imagepath + "/spinner_big.gif"});
			new Ajax.Request('happybirthday.php?ajax=1&action=addwish&pid='+pid+'&tid='+tid, {method: 'post', onComplete: function(request) { itsmybirthday.addWishesDone(request, pid, tid); }});
			document.body.style.cursor = 'wait';
			return false;
		}
		else
		{
			return true;
		}
	},
	
	addWishesDone: function(request, pid, tid)
	{
		if(request.responseText.match(/<error>([^<]*)<\/error>/))
		{
			message = request.responseText.match(/<error>([^<]*)<\/error>/);

			if(!message[1])
			{
				message[1] = "An unknown error occurred.";
			}

			if(this.spinner)
			{
				this.spinner.destroy();
				this.spinner = '';
			}
			document.body.style.cursor = 'default';
			alert('There was an error adding your wish:\n\n'+message[1]);
		}
		else
		{
			$("imb_"+pid).style.display = "";
			$("imb_"+pid).update(request.responseText);
			if(imb_wishesRemovable == 1)
			{
				$("imb_i"+pid).src = $("imb_i"+pid).src.replace("postbit_bday_add.gif", "postbit_bday_del.gif"); 
				$("imb_a"+pid).href = $("imb_a"+pid).href.replace('happybirthday.php?action=addwish&tid='+tid+'&pid='+pid, 'happybirthday.php?action=delwish&tid='+tid+'&pid='+pid);
				$("imb_a"+pid).onclick = new Function("",'return itsmybirthday.delWishes('+pid+', '+tid+');');
				$("imb_a"+pid).title = $("imb_a"+pid).title.replace("Add Birthday Wishes", "Remove Birthday Wishes");
			}
			else
			{
				$("imb_i"+pid).remove();
				$("imb_a"+pid).remove(); 
			}
		}
		
		if(this.spinner)
		{
			this.spinner.destroy();
			this.spinner = '';
		}
		document.body.style.cursor = 'default';
	},
	
	delWishes: function(pid, tid)
	{
		if(use_xmlhttprequest == 1)
		{
			this.spinner = new ActivityIndicator("body", {image: imagepath + "/spinner_big.gif"});
			new Ajax.Request('happybirthday.php?ajax=1&action=delwish&pid='+pid+'&tid='+tid, {method: 'post', onComplete: function(request) { itsmybirthday.delWishesDone(request, pid, tid); }});
			document.body.style.cursor = 'wait';
			return false;
		}
		else
		{
			return true;
		}
	},
	
	delWishesDone: function(request, pid, tid)
	{
		if(request.responseText.match(/<error>([^<]*)<\/error>/))
		{
			message = request.responseText.match(/<error>([^<]*)<\/error>/);

			if(!message[1])
			{
				message[1] = "An unknown error occurred.";
			}

			if(this.spinner)
			{
				this.spinner.destroy();
				this.spinner = '';
			}
			document.body.style.cursor = 'default';
			alert('There was an error deleting your wish:\n\n'+message[1]);
		}
		else
		{
			if(request.responseText == '')
			{
				$("imb_"+pid).style.display = "none";
			}
			$("imb_"+pid).update(request.responseText);
			$("imb_i"+pid).src = $("imb_i"+pid).src.replace("postbit_bday_del.gif", "postbit_bday_add.gif"); 
			$("imb_a"+pid).href = $("imb_a"+pid).href.replace('happybirthday.php?action=delwish&tid='+tid+'&pid='+pid, 'happybirthday.php?action=addwish&tid='+tid+'&pid='+pid);
			$("imb_a"+pid).onclick = new Function("",'return itsmybirthday.addWishes('+pid+', '+tid+');');
			$("imb_a"+pid).title = $("imb_a"+pid).title.replace("Remove Birthday Wishes", "Add Birthday Wishes");
		}
		
		if(this.spinner)
		{
			this.spinner.destroy();
			this.spinner = '';
		}
		document.body.style.cursor = 'default';
	}
};
Event.observe(document, 'dom:loaded', itsmybirthday.init);