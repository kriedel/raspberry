var thankyoulike = {
	init: function()
	{
	},
	
	tgl: function(pid)
	{
		if(tylCollapsible == 1)
		{
			if($('tyl_data_'+pid).visible())
			{
				$('tyl_data_'+pid).hide();
				$('tyl_title_'+pid).hide();
				$('tyl_title_collapsed_'+pid).show();
				$('tyl_i_expcol_'+pid).src = $('tyl_i_expcol_'+pid).src.replace("collapse.gif", "collapse_collapsed.gif");
				$('tyl_i_expcol_'+pid).alt = "[+]";
				$('tyl_a_expcol_'+pid).title = "[+]";
			}
			else
			{
				$('tyl_data_'+pid).show();
				$('tyl_title_collapsed_'+pid).hide();
				$('tyl_title_'+pid).show();
				$('tyl_i_expcol_'+pid).src = $('tyl_i_expcol_'+pid).src.replace("collapse_collapsed.gif", "collapse.gif");
				$('tyl_i_expcol_'+pid).alt = "[-]";
				$('tyl_a_expcol_'+pid).title = "[-]";
			}
		}
	},
	
	add: function(pid)
	{
		if(use_xmlhttprequest == 1 && tylEnabled == 1)
		{
			if(tylUser == 0)
			{
				return true;
			}
			this.spinner = new ActivityIndicator("body", {image: imagepath + "/spinner_big.gif"});
			new Ajax.Request('thankyoulike.php?ajax=1&action=add&pid='+pid+'&my_post_key='+my_post_key, {method: 'post', onComplete: function(request) { thankyoulike.addDone(request, pid); }});
			document.body.style.cursor = 'wait';
			return false;
		}
		else
		{
			return true;
		}
	},
	
	addDone: function(request, pid)
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
			alert(message[1]);
		}
		else
		{
			tylVisible = 2;
			if(tylCollapsible == 1 && $("tyl_"+pid).style.display != "none")
			{	
				if($('tyl_data_'+pid).visible())
				{
					tylVisible = 1;
				}
				else
				{
					tylVisible = 0;
				}
			}
			$("tyl_"+pid).update(request.responseJSON.tylData);
			$("tyl_"+pid).style.display = "";
			$("tyl_btn_"+pid).update(request.responseJSON.tylButton);
			if(tylCollapsible == 1)
			{
				if(tylVisible != 2)
				{
					if(tylVisible == 1)
					{
						$('tyl_data_'+pid).show();
						$('tyl_title_collapsed_'+pid).hide();
						$('tyl_title_'+pid).show();
						$('tyl_i_expcol_'+pid).src = $('tyl_i_expcol_'+pid).src.replace("collapse_collapsed.gif", "collapse.gif");
						$('tyl_i_expcol_'+pid).alt = "[-]";
						$('tyl_a_expcol_'+pid).title = "[-]";
					}
					else
					{
						$('tyl_data_'+pid).hide();
						$('tyl_title_'+pid).hide();
						$('tyl_title_collapsed_'+pid).show();
						$('tyl_i_expcol_'+pid).src = $('tyl_i_expcol_'+pid).src.replace("collapse.gif", "collapse_collapsed.gif");
						$('tyl_i_expcol_'+pid).alt = "[+]";
						$('tyl_a_expcol_'+pid).title = "[+]";
					}
				}
			}
		}
		
		if(this.spinner)
		{
			this.spinner.destroy();
			this.spinner = '';
		}
		document.body.style.cursor = 'default';
	},
	
	del: function(pid)
	{
		if(use_xmlhttprequest == 1 && tylEnabled == 1)
		{
			if(tylUser == 0)
			{
				return true;
			}
			this.spinner = new ActivityIndicator("body", {image: imagepath + "/spinner_big.gif"});
			new Ajax.Request('thankyoulike.php?ajax=1&action=del&pid='+pid+'&my_post_key='+my_post_key, {method: 'post', onComplete: function(request) { thankyoulike.delDone(request, pid); }});
			document.body.style.cursor = 'wait';
			return false;
		}
		else
		{
			return true;
		}
	},
	
	delDone: function(request, pid)
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
			alert(message[1]);
		}
		else
		{
			tylVisible = 2;
			if(tylCollapsible == 1 && $("tyl_"+pid).style.display != "none")
			{
				if($('tyl_data_'+pid).visible())
				{
					tylVisible = 1;
				}
				else
				{
					tylVisible = 0;
				}
			}
			if(request.responseJSON.tylData == '')
			{
				$("tyl_"+pid).style.display = "none";
			}
			$("tyl_"+pid).update(request.responseJSON.tylData);
			$("tyl_btn_"+pid).update(request.responseJSON.tylButton);
			if(tylCollapsible == 1 && $("tyl_"+pid).style.display != "none")
			{
				if(tylVisible != 2)
				{
					if(tylVisible == 1)
					{
						$('tyl_data_'+pid).show();
						$('tyl_title_collapsed_'+pid).hide();
						$('tyl_title_'+pid).show();
						$('tyl_i_expcol_'+pid).src = $('tyl_i_expcol_'+pid).src.replace("collapse_collapsed.gif", "collapse.gif");
						$('tyl_i_expcol_'+pid).alt = "[-]";
						$('tyl_a_expcol_'+pid).title = "[-]";
					}
					else
					{
						$('tyl_data_'+pid).hide();
						$('tyl_title_'+pid).hide();
						$('tyl_title_collapsed_'+pid).show();
						$('tyl_i_expcol_'+pid).src = $('tyl_i_expcol_'+pid).src.replace("collapse.gif", "collapse_collapsed.gif");
						$('tyl_i_expcol_'+pid).alt = "[+]";
						$('tyl_a_expcol_'+pid).title = "[+]";
					}
				}
			}
		}
		
		if(this.spinner)
		{
			this.spinner.destroy();
			this.spinner = '';
		}
		document.body.style.cursor = 'default';
	}
};	
Event.observe(document, 'dom:loaded', thankyoulike.init);