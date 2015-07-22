var xHttp;
(function($) {
	
	xHttp = 
	{
			
		get: function(url, successHandler, errorHandler, completeHandler)
		{
			$.get(url, function(response)
			{
				if (successHandler !== undefined)
				{
					successHandler(response);
				}
				if (completeHandler !== undefined)
				{
					completeHandler(true, response);
				}
			})
			.fail(function (xhr)
			{
				xHttp._onFail(xhr, errorHandler);
				
				if (completeHandler !== undefined)
				{
					completeHandler(false, response);
				}
			});
		},
		
		post: function(url, data, successHandler, errorHandler, completeHandler, ignore501)
		{
			$.post(url, data, function(response)
			{
				if (successHandler)
				{
					successHandler(response);
				}
				
				if (completeHandler !== undefined)
				{
					completeHandler(true, response);
				}
					
			})
			.fail(function(xhr)
			{
				xHttp._onFail(xhr, errorHandler, ignore501);
				
				if (completeHandler !== undefined)
				{
					completeHandler(false, xhr.responseText);
				}
			});
		},
		
		/**
		 * @param target - css selector for target element
		 * @param url - source URL
		 * @param preLoadHandler - callback function to affect the response before it will be injected into target. should return modified response body
		 * @param postLoadHandler - fires after content was loaded and DOM was modified or after errorHandler triggered
		 * @param errorHandler
		 * @param mode - can be 'append' or 'overwrite'. NOTE(!) that 'overwrite' used by default if paramater was not passed or passed as undefined
		 */
		load: function(target, url, preLoadHandler, postLoadHandler, errorHandler, mode)
		{
			$.get(url, function(response)
			{
				content = response;
				if (preLoadHandler !== undefined)
				{
					content = preLoadHandler(response);
				}
				
				if (mode == 'append')
				{
					$(target).append(content);
				}
				else
				{
					$(target).html(content);
				}
				
				if (postLoadHandler !== undefined)
				{
					postLoadHandler();
				}
				
			})
			.fail(function (xhr)
			{
				xHttp._onFail(xhr, errorHandler);
				
				if (postLoadHandler !== undefined)
				{
					postLoadHandler();
				}
			});
		},
		
		_onFail: function(xhr, errorHandler, ignore501)
		{
			if (xhr.status == 501 && ignore501 !== false)
			{
				return;
			}
			
			if (errorHandler == undefined)
			{
				ajaxErrorHandler.handle(xhr);
				return;
			}
			
			errorHandler(xhr);
		}
		
	}
	
})(jQuery);