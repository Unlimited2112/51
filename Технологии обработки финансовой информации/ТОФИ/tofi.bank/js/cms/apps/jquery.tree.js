var jq_tree_button_up = '';
var jq_tree_button_up_inact = '';
var jq_tree_button_down = '';
var jq_tree_button_down_inact = '';
var jq_tree_button_delete = '';
var jq_tree_button_delete_inact = '';
var jq_tree_button_add = '';
var jq_tree_button_add_inact = '';
var jq_tree_button_edit = '';
var jq_tree_button_edit_inact = '';

var jq_tree_selected_item = '';

var jq_tree_add_url = '';
var jq_tree_edit_url = '';
var jq_tree_content_url = '';
var jq_tree_ajax_url = '';

var jq_tree_box_id = 'treetable';
var jq_tree_line_active = '#EDEDED';
var jq_tree_line_selected = '#EDEDED';

var jq_tree_confirm_text = '';
var jq_tree_last_data = {};
var jq_tree_treads_status = {};

var jq_tree_img_dir = '';

var jq_tree_img_line =  "/images/cms/tree/line.gif";
var jq_tree_img_minus =  "/images/cms/tree/minus.gif";
var jq_tree_img_minusbottom =  "/images/cms/tree/minusbottom.gif";
var jq_tree_img_minustoproot =  "/images/cms/tree/minustoproot.gif";
var jq_tree_img_minusonlyroot =  "/images/cms/tree/minusonlyroot.gif";
var jq_tree_img_plus =  "/images/cms/tree/plus.gif";
var jq_tree_img_plusbottom =  "/images/cms/tree/plusbottom.gif";
var jq_tree_img_plusonlyroot =  "/images/cms/tree/plusonlyroot.gif";
var jq_tree_img_plustoproot =  "/images/cms/tree/plustoproot.gif";
var jq_tree_img_join =  "/images/cms/tree/join.gif";
var jq_tree_img_joinbottom =  "/images/cms/tree/joinbottom.gif";
var jq_tree_img_joinonlyroot =  "/images/cms/tree/joinonlyroot.gif";
var jq_tree_img_sign_plus =  "/images/cms/tree/sign_plus.gif";
var jq_tree_img_sign_minus =  "/images/cms/tree/sign_minus.gif";
var jq_tree_img_x =  "/images/cms/tree/x.gif";

function jq_tree_get_tree() {
	$.getJSON(
	  jq_tree_ajax_url,
	  {
	  	operation: "getTree"
	  },
	  jq_tree_generate
	);
}

function jq_tree_send_action(btn, id) {
	$.getJSON(
	  jq_tree_ajax_url,
	  {
	  	operation: "getTree",
	  	action: btn,
	  	id_item: id
	  },
	  jq_tree_generate
	);
}


function jq_tree_generate(tree_data) {
	jq_tree_last_data = tree_data;
	
	var start_branch='';
	var end_branch='';
	var padding='';
	var img='';
	var str='';	
	var src='';
	var buttons = '';
	var status_class = '';
	var item_css = '';
	var fix = '';

	for( var id in tree_data ) {
		
		buttons = "";
		if ( !parseInt(id) && id!=0  ) continue;

		padding='';
		if ( (jq_tree_selected_item == tree_data[id]['id_item']) && (fix == "")) 
		{
			jq_tree_selected_item = id;
			fix= "fix";
		}

		for( var i=0; i<tree_data[id]['level']; i++ ) {
			if ( tree_data[id]['levels'][i] ) {
				padding+='<img align="top" border="0" alt="" src="/images/cms/tree/line.gif" height="21" width="17" />';
			} else {
				padding+='<img align="top" border="0" alt="" src="/images/cms/tree/x.gif" height="21" width="17"  />';
			}
		}

		src='';

		if ( tree_data[id]['children'].length ) {
			if(id>0) {
				if ( tree_data[id]['last'] ) {
					if ( tree_data[id]['sequence']>1 ) {
						buttons = jq_tree_get_button('button_up',id)+jq_tree_button_down_inact;
					} else {
						buttons = jq_tree_button_up_inact+jq_tree_button_down_inact;
					}
					src= "/images/cms/tree/minusbottom.gif";
				} else {
					if ( tree_data[id]['sequence']>1 ) {
						buttons = jq_tree_get_button('button_up',tree_data[id]['id_item'])+jq_tree_get_button('button_down', tree_data[id]['id_item']);
					} else {
						buttons = jq_tree_button_up_inact+jq_tree_get_button('button_down',tree_data[id]['id_item']);
					}
					src= "/images/cms/tree/minus.gif";
				}
				
				buttons = buttons+jq_tree_get_button('button_edit',tree_data[id]['id_item']);
				
				if (tree_data[id]['can_add'] != 0) {
					buttons = buttons+jq_tree_get_button('button_add',tree_data[id]['id_item']);
				} else {
					buttons = buttons+jq_tree_button_add_inact;
				}						
			} else {
				if ( tree_data[id]['last'] && id==0 ) {
					if (tree_data[id]['can_add'] != 0) {
						buttons = jq_tree_get_button('button_add',tree_data[id]['id_item']);
					} else {
						buttons = buttons+jq_tree_button_add_inact;
					}
					src= "/images/cms/tree/minusonlyroot.gif";
				} else {
					if ( !tree_data[id]['last'] ) {
						src= "/images/cms/tree/minusbottom.gif";
					} else {
						if (id==0) {
							if (tree_data[id]['can_add'] != 0) {
								buttons = jq_tree_get_button('button_add',tree_data[id]['id_item']);
							} else {
								buttons = buttons+jq_tree_button_add_inact;
							}
							src= "/images/cms/tree/minustoproot.gif";
						} else {
							src= "/images/cms/tree/minus.gif";
						}
					}
				}
			}
			jq_tree_treads_status[id] = 'plus';
			img = '<a href="#" onclick="jq_tree_change_state(\''+id+'\',\'click\'); return false;"><img src="'+src+'" id="img_'+id+'" alt="" align="top" border="0" height="21" width="17" /></a>';
		} else {
			if (id>0) {
				if ( tree_data[id]['last'] ) {
					if ( tree_data[id]['sequence']>1 ) {
						buttons = jq_tree_get_button('button_up',tree_data[id]['id_item'])+jq_tree_button_down_inact;
					} else {
						buttons = jq_tree_button_up_inact+jq_tree_button_down_inact;
					}
					src= "/images/cms/tree/joinbottom.gif";
				} else {
					if ( tree_data[id]['sequence']>1 ) {
						buttons = jq_tree_get_button('button_up',tree_data[id]['id_item'])+jq_tree_get_button('button_down',tree_data[id]['id_item']);
					} else {
						buttons = jq_tree_button_up_inact+jq_tree_get_button('button_down',tree_data[id]['id_item']);
					}
					src= "/images/cms/tree/join.gif";
				}
				
				buttons = buttons+jq_tree_get_button('button_edit',tree_data[id]['id_item']);
				
				if (tree_data[id]['can_add'] != 0) {
					buttons = buttons+jq_tree_get_button('button_add',tree_data[id]['id_item']);
				} else {
					buttons = buttons+jq_tree_button_add_inact;
				}
			} else {
				if ( tree_data[id]['last'] && id==0 ) {
					if (tree_data[id]['can_add'] != 0) {
						buttons = jq_tree_get_button('button_add',tree_data[id]['id_item']);
					} else {
						buttons = buttons+jq_tree_button_add_inact;
					}
					src= "/images/cms/tree/joinonlyroot.gif";
				} else {
					if ( tree_data[id]['last'] ) {
						src= "/images/cms/tree/joinbottom.gif";
					} else {
						if (id==0) {
							if (tree_data[id]['can_add'] != 0) {
								buttons = jq_tree_get_button('button_add',tree_data[id]['id_item']);
							} else {
								buttons = buttons+jq_tree_button_add_inact;
							}
							src= "/images/cms/tree/jointoproot.gif";
						} else {
							src= "/images/cms/tree/join.gif";
						}
					}
				}
			}
			img = '<img alt="" id="img_'+id+'" src="'+src+'" align="top" border="0" height="21" width="17" />';
		}

		padding+=img;
		end_branch = '';

		for ( var i=0; i<tree_data[id]['level_up']; i++ ) {
			end_branch+='</div>';
		}

		start_branch = '';
		if ( tree_data[id]['children'].length>0 ) {
			start_branch = '<div id="children_'+id+'">';
		}

		if ( tree_data[id]['undeletable'] == 0 ) {
			if ( id > 0 ) {
				buttons = jq_tree_get_button('button_delete',tree_data[id]['id_item']) + buttons;
			}
		}				
		else if ( tree_data[id]['undeletable'] == 2 ) {
			if ( id > 0 ) {
				buttons = jq_tree_button_delete_inact + buttons;
			}
		} else {
			buttons = jq_tree_button_delete_inact+buttons;
		}

		status_class = 'tree_leaf_b';
		item_css = 'undraglist';

		str+='<div class="table" id="tab_id_'+id+'"><table border="0" cellpadding="0" cellspacing="0"><tr id="tr_'+id+'"><td class="td_tree" width="0">'+padding+'<span class="'+item_css+'" id="drag_item_'+id+'"><span id="mnu_'+id+'" class="'+status_class+'">'+tree_data[id]['title']+'</span></span></td><td width="100%"><div class="fill"></div></td><td class="td_actions" width="0"><span class="'+status_class+'">'+buttons+'</span></td></tr></table></div>'+end_branch+start_branch;
	}

	$('#'+jq_tree_box_id+'').html(str);
	
	////////////////////////////////////////	
	
	$('#'+jq_tree_box_id+' table tr').hover(
		function() {
		  $(this).css('backgroundColor', jq_tree_line_active);
		},
		function() {
		  $(this).css('backgroundColor', 'transparent');
		}
	);
	
	if(jq_tree_selected_item != "")	{
		$('#tr_'+ jq_tree_selected_item).css('backgroundColor', jq_tree_line_selected);
		$('#tr_'+ jq_tree_selected_item).unbind( "hover" ).hover(
			function() {
			  $(this).css('backgroundColor', jq_tree_line_selected);
			},
			function() {
			  $(this).css('backgroundColor', jq_tree_line_selected);
			}
		);
	}
}


function jq_tree_get_button ( btn, id ) {
	if ( ( btn == 'button_up' ) || ( btn == 'button_down' ) ) {
		return '<a href="#" onclick="javascript: jq_tree_send_action(\''+btn+'\','+id+'); return false">'+eval('jq_tree_' + btn)+'</a>';
	}
	else if ( btn == 'button_edit') {
		return '<a href="'+jq_tree_edit_url+id+'/" class="'+btn+'">'+eval('jq_tree_' + btn)+'</a>';
	}
	else if ( btn == 'button_add') {
		return '<a href="'+jq_tree_add_url+id+'/" class="'+btn+'">'+eval('jq_tree_' + btn)+'</a>';
	}
	else if ( btn == 'button_delete') {
		return '<a href="#" onclick="if ( confirm(\'' + jq_tree_confirm_text + '\') ) jq_tree_send_action(\''+btn+'\','+id+'); return false">'+eval('jq_tree_' + btn)+'</a>';
	}
	else return "";
}		

function jq_tree_change_state(id,action) {
	var tree_data = jq_tree_last_data;

	if ( !tree_data[id]['children'].length ) {
		return;
	}

	var d_id = '#children_'+id;
	var i_id = 'img_'+id;
	var type = '';
	var state = ['plus','minus'];

	if(jq_tree_treads_status[id] == 'plus') {
		$(d_id).hide();
		jq_tree_treads_status[id] = 'minus';
		state[0] = 'plus';
		state[1] = 'minus';
	} else {
		$(d_id).show();
		jq_tree_treads_status[id] = 'plus';
		state[0] = 'minus';
		state[1] = 'plus';
	}

	var t_str = jq_tree_img_x;
	t_str = t_str.replace(/x.gif/,'');
	var r = new RegExp('^.*?('+t_str+')');
	t_str = document.getElementById(i_id).src;
	t_str = t_str.replace(r, '$1');

	switch ( t_str ) {
		case eval('jq_tree_img_sign_'+state[0]):
			state[1] = 'sign_'+state[1]
			type='';
			break;
		case eval('jq_tree_img_'+state[0]+'bottom'):
			type='bottom';
			break;
		case eval('jq_tree_img_'+state[0]+'onlyroot'):
			type='onlyroot';
			break;
		case eval('jq_tree_img_'+state[0]+'toproot'):
			type='toproot';
			break;
		default:
			break;
	}

	document.getElementById(i_id).src = eval('jq_tree_img_'+state[0]+type);
}