(function() {
	tinymce.PluginManager.add('tie_insta_mce_button', function( editor, url ) {
		editor.addButton( 'tie_insta_mce_button', {
			icon    : ' tie-insta-shortcodes-icon ',
			tooltip : tieinsta_js.shortcodes_tooltip,
			type    : 'button',
			minWidth: 210,

			onclick: function() {
				var tieinstaWin = editor.windowManager.open({
					title: 'Instagram',
					body: [
						{
							type: 'checkbox',
							name: 'InstagramLogo',
							label: tieinsta_js.logo_bar,
							value: 'true',
						},
						{
							type: 'checkbox',
							name: 'newWindow',
							label: tieinsta_js.new_window,
							value: 'true',
						},
						{
							type: 'checkbox',
							name: 'Nofollow',
							label: tieinsta_js.nofollow,
							value: 'true',
						},
						{
							type: 'checkbox',
							name: 'Credit',
							label: tieinsta_js.credit,
							value: 'true',
						},
						{
							type: 'label',
							name: 'sep1',
							onPostRender : function() {this.getEl().innerHTML = "<br />"}
						},
						{
							type: 'checkbox',
							name: 'InfoArea',
							id: 'InfoArea',
							label: tieinsta_js.account_info,
							value: 'true',
						},
						{
							type: 'listbox',
							name: 'Position',
							id: 'Position',
							label: tieinsta_js.position,
							'values': [
								{text: tieinsta_js.top, value: 'top'},
								{text: tieinsta_js.bottom, value: 'bottom'},
							]
						},
						{
							type: 'checkbox',
							name: 'FullName',
							id: 'FullName',
							label: tieinsta_js.full_name,
							value: 'true',
						},
						{
							type: 'checkbox',
							name: 'Website',
							id: 'Website',
							label: tieinsta_js.website_url,
							value: 'true',
						},
						{
							type: 'checkbox',
							name: 'bio',
							id: 'bio',
							label: tieinsta_js.bio,
							value: 'true',
						},
						{
							type: 'checkbox',
							name: 'Stats',
							id: 'Stats',
							label: tieinsta_js.account_stats,
							value: 'true',
						},
						{
							type: 'listbox',
							name: 'AvatarShape',
							id: 'AvatarShape',
							label: tieinsta_js.avatar_shape,
							'values': [
								{text: tieinsta_js.square, value: 'square'},
								{text: tieinsta_js.round, value: 'round'},
								{text: tieinsta_js.circle, value: 'circle'},
							]
						},
						{
							type: 'textbox',
							name: 'AvatarSize',
							id: 'AvatarSize',
							label: tieinsta_js.avatar_size,
							value: '70'
						},
						{
							type: 'label',
							name: 'sep2',
							onPostRender : function() {this.getEl().innerHTML = "<br />"}
						},
						{
							type: 'listbox',
							name: 'NumberMedia',
							label: tieinsta_js.media_items,
							'values': [
								{text: '1', value: '1'},
								{text: '2', value: '2'},
								{text: '3', value: '3'},
								{text: '4', value: '4'},
								{text: '5', value: '5'},
								{text: '6', value: '6'},
								{text: '7', value: '7'},
								{text: '8', value: '8'},
								{text: '9', value: '9'},
								{text: '10', value: '10'},
								{text: '11', value: '11'},
								{text: '12', value: '12'},
								{text: '13', value: '13'},
								{text: '14', value: '14'},
								{text: '15', value: '15'},
								{text: '16', value: '16'},
								{text: '17', value: '17'},
								{text: '18', value: '18'},
								{text: '19', value: '19'},
								{text: '20', value: '20'},
							]
						},
						{
							type: 'listbox',
							name: 'link',
							label: tieinsta_js.link_to,
							'values': [
								{text: tieinsta_js.media_page, value: 'page'},
								{text: tieinsta_js.none, value: 'none'},
							]
						},
						{
							type: 'listbox',
							name: 'MediaLayout',
							id: 'MediaLayout',
							label: tieinsta_js.layout,
							'values': [
								{text:  tieinsta_js.grid, value: 'grid'},
								{text:  tieinsta_js.slider, value: 'slider'},
							],
							onselect: function( ) {
								if (this.value() == 'slider') {
									tinyMCE.DOM.setStyle( 'GridColumns-l', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridColumns', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridFlat-l', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridFlat', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridLoadMore-l', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridLoadMore', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridLoadMoreNumber-l', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridLoadMoreNumber', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'SliderSpeed', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'SliderSpeed-l', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'SliderAnima', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'SliderAnima-l', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'SliderMedia', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'SliderMedia-l', 'opacity', '1.0');
								}
								else {
									tinyMCE.DOM.setStyle( 'GridColumns-l', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridColumns', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridFlat-l', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridFlat', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridLoadMore-l', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridLoadMore', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridLoadMoreNumber-l', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridLoadMoreNumber', 'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'SliderSpeed', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'SliderSpeed-l', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'SliderAnima', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'SliderAnima-l', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'SliderMedia', 'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'SliderMedia-l', 'opacity', '0.4');
								}
							},
						},
						{
							type: 'listbox',
							name: 'Columns',
							id: 'GridColumns',
							label:    tieinsta_js.columns,
							'values': [
								{text: '1', value: '1'},
								{text: '2', value: '2'},
								{text: '3', value: '3'},
								{text: '4', value: '4'},
								{text: '5', value: '5'},
								{text: '6', value: '6'},
								{text: '7', value: '7'},
								{text: '8', value: '8'},
								{text: '9', value: '9'},
								{text: '10', value: '10'},
							]
						},
						{
							type: 'checkbox',
							name: 'Flat',
							id: 'GridFlat',
							label: tieinsta_js.flat,
						},
						{
							type: 'textbox',
							name: 'speed',
							id: 'SliderSpeed',
							label: tieinsta_js.slider_speed,
							value: '3000'
						},
						{
							type: 'listbox',
							name: 'Effect',
							id: 'SliderAnima',
							label:    tieinsta_js.slider_effect,
							'values': [
								{text: 'blindX', value: 'blindX'},
								{text: 'blindY', value: 'blindY'},
								{text: 'blindZ', value: 'blindZ'},
								{text: 'cover', value: 'cover'},
								{text: 'curtainX', value: 'curtainX'},
								{text: 'curtainY', value: 'curtainY'},
								{text: 'fade', value: 'fade'},
								{text: 'fadeZoom', value: 'fadeZoom'},
								{text: 'growX', value: 'growX'},
								{text: 'growY', value: 'growY'},
								{text: 'scrollUp', value: 'scrollUp'},
								{text: 'scrollDown', value: 'scrollDown'},
								{text: 'scrollLeft', value: 'scrollLeft'},
								{text: 'scrollRight', value: 'scrollRight'},
								{text: 'scrollHorz', value: 'scrollHorz'},
								{text: 'scrollVert', value: 'scrollVert'},
								{text: 'slideX', value: 'slideX'},
								{text: 'slideY', value: 'slideY'},
								{text: 'toss', value: 'toss'},
								{text: 'turnUp', value: 'turnUp'},
								{text: 'turnDown', value: 'turnDown'},
								{text: 'turnLeft', value: 'turnLeft'},
								{text: 'turnRight', value: 'turnRight'},
								{text: 'uncover', value: 'uncover'},
								{text: 'wipe', value: 'wipe'},
								{text: 'zoom', value: 'zoom'},
							]
						},
						{
							type: 'checkbox',
							name: 'commentsLikes',
							id: 'SliderMedia',
							label: tieinsta_js.comments_likes,
							value: 'true',
						},
					],
					onsubmit: function( e ) {
						var output ;
						output = '[instanow';

						if( e.data.Position ) output += ' info_pos= ' + e.data.Position;
						if( e.data.InfoLayout ) output += ' info_layout= ' + e.data.InfoLayout;
						if( e.data.AvatarShape ) output += ' shape= ' + e.data.AvatarShape;
						if( e.data.AvatarSize ) output += ' size= ' + e.data.AvatarSize;
						if( e.data.InfoArea ) output += ' info= 1';
						if( e.data.FullName ) output += ' full_name= 1';
						if( e.data.Website ) output += ' website= 1';
						if( e.data.bio ) output += ' bio= 1';
						if( e.data.Stats ) output += ' stats= 1';
						if( e.data.newWindow ) output += ' window= 1';
						if( e.data.InstagramLogo ) output += ' logo= 1';
						if( e.data.Nofollow ) output += ' nofollow= 1';
						if( e.data.Credit ) output += ' credit= 1';
						if( e.data.BoxStyle ) output += ' style= ' + e.data.BoxStyle;
						if( e.data.NumberMedia ) output += ' media= ' + e.data.NumberMedia;
						if( e.data.link ) output += ' link= ' + e.data.link;
						if( e.data.MediaLayout ) output += ' layout= ' + e.data.MediaLayout;

						if( e.data.MediaLayout == 'slider' ){

							if( e.data.Effect ) output += ' effect= ' + e.data.Effect;
							if( e.data.speed ) output += ' speed= ' + e.data.speed;
							if( e.data.commentsLikes ) output += ' com_like= 1';

						}else{

							if( e.data.Columns ) output += ' columns= ' + e.data.Columns;
							if( e.data.Flat ) output += ' flat= 1';
							if( e.data.LoadMore ) output += ' lm= 1';
							if( e.data.LoadMoreNumber ) output += ' lm_num= ' + e.data.LoadMoreNumber;

						}

						output += ' ]';
						editor.insertContent( output );

					},
					onrepaint: function( e ) {
						tinyMCE.DOM.setStyle( 'SliderSpeed',  'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'SliderSpeed-l',  'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'SliderAnima',  'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'SliderAnima-l',  'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'SliderMedia',  'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'SliderMedia-l',  'opacity', '0.4');
					},

				});
			}
		});
	});
})();