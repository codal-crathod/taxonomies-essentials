/* Plugin Taxonomy Essentials Script
---------------------------------------------------*/
// Tabs for admin page
jQuery( "#tabs" ).tabs({
	activate: function( event, ui ) {
		var scrollTop = jQuery(window).scrollTop();
		location.hash = ui.newPanel.attr('id');
		jQuery(window).scrollTop(scrollTop);
	}
});

// Accordion for admin page
jQuery('.tx-essentials-accordian').accordion({heightStyle: "content",collapsible: true,active: false});

jQuery(window).on('load', function () {
	var $ = jQuery;

	// Checkbox related
	$('.selectit.any > input').change(function(){
		console.log('chang');
		$(this).parents('div:first').find('ul:first').toggleClass('disabled');
	});


	// Post Essentials
	jQuery('#publish, .editor-post-publish-panel__toggle, .editor-post-publish-button').click(function (e) {
		var valid = true;
		var errmessage = ''; // txv_options.err_msg_taxonomy || txv_options.err_msg_term;
		var term_required = txv_options.required
		if ( term_required && txv_options.ptype ) {
			console.log('r exist');
			$.each(term_required[txv_options.ptype], function (taxonomy, required_terms) {

				var rest_base = txv_options[taxonomy] || taxonomy;
				var label     = txv_options[taxonomy+'-label'] || taxonomy;

				// If the taxonomy metabox is not visible, we don't do any essentials
				var $scope = $('#' + taxonomy + 'div, .components-panel__body-toggle:contains(' + label + '), #tagsdiv-' + taxonomy + ', .wc-metabox.' + taxonomy).first();

				console.log('ptype == ptype'); console.log(rest_base);
				if ($('.components-panel__body-toggle').length) {
					console.log('block editor')
					var postData = jQuery.extend({}, wp.data.select("core/editor").getCurrentPost(), wp.data.select("core/editor").getPostEdits());
					if (typeof postData[rest_base] !== 'undefined') {
						// if uncategorised
						missing = [];
						edited  = JSON.parse('['+postData[rest_base]+']');
						console.log('edited:'+edited);
						$.each(required_terms, function (key, value) {
							console.log('key'+key+' value'+value);
							// if any one term is required
							if (value == 'any') {
								if (edited.length > 0) {
									return false;
								}
								errmessage += txv_options.err_msg_taxonomy.replace('{taxonomy-name}', label)+'\n';
								valid = false;
								return false;
							}else if (!edited.includes(parseInt(value))) {
								missing.push(txv_options[value]);
								console.log('block editor error required');
							}
						});
						console.log('miss:');
						console.log(missing);
						if (missing.length > 0) {
							valid = false;
							msg = txv_options.err_msg_term.replace('{term-list}', missing.join(', '));
							errmessage += msg.replace('{taxonomy-name}', label)+'\n';
						}
					}
				} else {

					console.log('classic editor:');

					var any = $scope.find('input:checked').length > 0 || $scope.find('textarea').val();
						
					console.log($scope.find('input:checked'));
					console.log($scope.find('textarea').val());
					edited = [];
					missing = [];
					edited_non_hierarchical = [];
					$.each($scope.find('input:checked'), function( index, input ) {
						edited.push(parseInt($(input).val()));
					});

					if (typeof $scope.find('textarea').val() !== 'undefined' &&
						$scope.find('textarea').val() != '') {
						console.log('textarea:');
						console.log($scope.find('textarea').val());
						edited_non_hierarchical = $scope.find('textarea').val().split(",");

						/*$.each(non_hierarchical, function( index, value ) {
							edited.push(parseInt(value));
						});*/
					}

					console.log('edited:');console.log(edited);
					
					$.each(required_terms, function (key, value) {

						console.log('key'+key+' value'+value);
						// if any one term is required
						if( value == 'any' ) {
							if (edited.length > 0) {
								return false;
							}
							errmessage += txv_options.err_msg_taxonomy.replace('{taxonomy-name}', label)+'\n';
							valid = false;
							return false;
						}
						
						if (edited_non_hierarchical.length > 0 && txv_options[value] ) {
							if (!edited_non_hierarchical.includes((txv_options[value]))) {
								missing.push(txv_options[value]);
								console.log('non_hierarchical error required');
							}
						}else{
							if (!edited.includes(parseInt(value))) {
								missing.push(txv_options[value]);
								console.log('classic editor error required');
							}
						}

					});
				
					if (missing.length > 0) {

						valid = false;
						msg = txv_options.err_msg_term.replace('{term-list}', missing.join(', '));
						errmessage += msg.replace('{taxonomy-name}', label)+'\n';
					}

					
				}
				console.log(postData);
			
			});

		}

		if (term_selected = txv_options.selected) {
			console.log('s exist');
		}

		if (!valid) {
			confirm(errmessage);
		}
		console.log(txv_options);
		return valid;
	});

});