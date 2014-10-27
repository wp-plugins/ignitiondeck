jQuery(document).ready(function() {
	jQuery('#id_account').magnificPopup({
		type: 'iframe',

		iframe: {
			patterns: {
				ignitiondeck: {
					index: 'ignitiondeck.com',

					//id: 'http://ignitiondeck.com/id/id-free-registration/',

					src: 'http://ignitiondeck.com/id/id-launchpad-checkout/',
				}

				//srcAction: '#id_account'
			}
		}
	});
	jQuery('#id_account').click(function(e) {
		//e.preventDefault();
	});

	window.addEventListener('message', idfRegister, false);

	// Themes Page

	jQuery('.activate_theme').click(function(e) {
		e.preventDefault();
		var slug = jQuery(this).data('theme');
		jQuery.ajax({
			url: idf_admin_ajaxurl,
			type: 'POST',
			data: {action: 'idf_activate_theme', theme: slug},
			success: function(res) {
				if (res == 1) {
					location.reload();
					//location.href= idf_siteurl + '/wp-admin/themes.php?page=theme-settings';
				}
			}
		});
	});
	// Extensions Page
	jQuery('.extension-link .active-installed').click(function(e) {
		e.preventDefault();
		var extension = jQuery(this).data('extension');
		jQuery.ajax({
			url: idf_admin_ajaxurl,
			type: 'POST',
			data: {action: 'idf_activate_extension', extension: extension},
			success: function(res) {
				if (res == 1) {
					location.reload();
				}
			}
		});
	});
	// new / edit post page
	if (idf_platform !== 'legacy') {
		jQuery('input[value="pwyw"]').attr('disabled', 'disabled');
	}

});
function idfRegister(e) {
	//console.log(e.data);
	if (e.data == 'idf: registered') {
		// they have completed registration
		setTimeout(function() {
			jQuery.magnificPopup.close();
			jQuery.ajax({
				url: idf_admin_ajaxurl,
				type: 'POST',
				data: {action: 'idf_registered'},
				success: function(res) {
					//console.log(res);
					location.reload();
				}
			});
		}, 1500);
		
	}
}