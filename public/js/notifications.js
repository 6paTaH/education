var smartAlert;
(function($) {
	
	smartAlert = 
	{
		color:
		{
			error:		'#C26565',
			success:	'#739E73',
			info:		'#3276B1',
			warning:	'#C79121'
		},
			
		small: function(sColor, sTitle, sContent, sIcon, sIconSmall, bSound, nTimeout)
		{
			$.smallBox({
				color 		: (sColor 		== (undefined || null) ? smartAlert.color.info : (smartAlert.color.hasOwnProperty(sColor) ? smartAlert.color[sColor] : sColor)),
				title 		: (sTitle 		== undefined ? 'Your title' : sTitle),
				content 	: (sContent 		== undefined ? 'Your content' : sContent),
				icon		: (sIcon 		== undefined ? 'fa fa-bell' : sIcon),
				iconSmall	: (sIconSmall 	== undefined ? 'botClose fa fa-times' : sIconSmall),
				sound		: (bSound 		== undefined ? true : bSound),
				timeout		: (nTimeout 		== undefined ? undefined : nTimeout),
			}); 
		},
		
		//Wrapper for errors
		smallError: function(text, sIcon, bSound, nTimeout)
		{
			sIcon 		= sIcon == undefined ? 'fa fa-warning bounce animated' : sIcon;
			nTimeout	= nTimeout == undefined ? 3000 : nTimeout;
			this.small('error', 'Error', text, sIcon, null, bSound, nTimeout);
		},
		
		// 501 Error alert wrapper
		smallError501: function()
		{
			this.small('error', '501 internal error', 'An error was occured during operation', 'fa fa-warning bounce animated', null, true);
		},
		
		//Wrapper for success alerts
		smallSuccess: function(text, sIcon, bSound, nTimeout)
		{
			sIcon 		= sIcon == undefined ? 'fa fa-check bounce animated' : sIcon;
			nTimeout	= nTimeout == undefined ? 3000 : nTimeout;
			this.small('success', 'Ok', text, sIcon, null, bSound, nTimeout);
		},
		
		big: function()
		{
			
		},
		
		confirm: function(title, content, callbackYes, callbackNo)
		{
			if (navigator.platform.indexOf('iPad') != -1)
			{
				$(document).bind('touchmove', function(e) {
					e.preventDefault();
				});
			}
			
			$.SmartMessageBox({
				
				title: title,
				content: content,
				buttons : '[No][Yes]'
					
			}, function(ButtonPressed) {
				
				if (navigator.platform.indexOf('iPad') != -1)//@todo вынести проверку isIpad в app.js
				{
					$(document).unbind('touchmove');
				}
				
				if (ButtonPressed === 'Yes' && callbackYes !== undefined)
				{
					callbackYes();
				}
				
				if (ButtonPressed === 'No' && callbackNo !== undefined)
				{
					callbackNo();
				}
				
			});
		},
		
	}
	
})(jQuery);