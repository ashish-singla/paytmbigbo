(function() {
                
	var bar = jQuery('.bex-bar');
	var percent = jQuery('.bex-percent');
	var status = jQuery('#bexStatus');
	var file = jQuery('#bexFile');
		   
	jQuery('#bexUpload').ajaxForm({
		data: {
			action: 'submit_content'
		},
		
		beforeSend: function() {
			status.html('Preparing to upload...');
			var percentVal = '0%';
			bar.width(percentVal);
			percent.html(percentVal);
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = parseInt(percentComplete * 0.8) + '%';
			bar.width(percentVal);
			percent.html(percentVal);
			status.html('Uploading...');
		},
		success: function() {
			status.html('Finishing...');
		},
		complete: function(xhr) {
			var percentVal = '100%';
			bar.width(percentVal);
			percent.html(percentVal);
			status.html(xhr.responseText);
			setTimeout(function(){
				file.val('');
				percentVal = '0%';
				bar.width(percentVal);
				percent.html(percentVal);
				status.html('File uploaded. Ready to upload.');
			}, 1000); 			
		}
	}); 

})();       