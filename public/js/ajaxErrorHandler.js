var ajaxErrorHandler
(function($) {
	
	ajaxErrorHandler =
	{
		error500: function()
		{
			$.ajaxSetup({
				statusCode: {
					501: function(xhr) {
						smartAlert.smallError501();
						ajaxErrorHandler._after501(xhr);
					}
				}
			});
		},
		
		handle: function(xhr)
		{
			code = xhr.status;
			switch (code)
			{
				case 400:
					smartAlert.smallError('400 Validation failed');
					break;
				case 403:
					break;
			}
		},
		
		_after501: function(xhr)//for extenging on pages if need
		{
		}
	}
	
})(jQuery);

$(function() {
	ajaxErrorHandler.error500();
});