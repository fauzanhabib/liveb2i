$(document).ready(function(){
	//check curruent page
	current_page = document.location.href;
	
	if (current_page.match(/account/)) {
		console.log('profile');
		$("ul.nav-side li.pure-menu-item.std-profile a").addClass('pure-menu-active');
	}

	else if (current_page.match(/student_partner/)) {
		if (current_page.match(/approve_token_requests/) || current_page.match(/history_token_requests/)) {
			$("ul.nav-side li.pure-menu-item.spr-atoken a").addClass('pure-menu-active');
		}
		else if (current_page.match(/request_token/)) {
			$("ul.nav-side li.pure-menu-item.spr-trequest a").addClass('pure-menu-active');
		}
		else if (current_page.match(/subgroup/) || current_page.match(/adding/) || current_page.match(/member_list/) || current_page.match(/student_upcoming_session/) || current_page.match(/student_histories/)) {
			$("ul.nav-side li.pure-menu-item.spr-sgroup a").addClass('pure-menu-active');
		}
		else if (current_page.match(/add_token/)) {
			$("ul.nav-side li.pure-menu-item.spr-addtoken a").addClass('pure-menu-active');
		}
	}

	else if (current_page.match(/superadmin/)) {
		if(current_page.match(/manage_partner/)){
			if(current_page.match(/approve_coach/)){
				$("ul.nav-side li.pure-menu-item.rad-partner a").addClass('pure-menu-active');
				$("ul.menu-dropdown.part li.pure-menu-item.rad-papproval a").addClass('active');
				$('.menu-dropdown.part').addClass('show');
			}  
			else if (current_page.match(/token/)) {
				$("ul.nav-side li.pure-menu-item.rad-partner a").addClass('pure-menu-active');
				$("ul.menu-dropdown.part li.pure-menu-item.rad-trapproval a").addClass('active');
				$('.menu-dropdown.part').addClass('show');
			}
			else if(current_page.match(/detail/) || current_page.match(/partner/) || current_page.match(/list_partner/) || current_page.match(/member_of_student/) || current_page.match(/student_detail/) || current_page.match(/member_of_coach/) || current_page.match(/coach_detail/)){
				$("ul.nav-side li.pure-menu-item.rad-region a").addClass('pure-menu-active');
			} 
		}
		else if (current_page.match(/settings/)){
			if (current_page.match(/region/)) {
				$("ul.nav-side li.pure-menu-item.rad-setting a").addClass('pure-menu-active');
				$("ul.menu-dropdown li.pure-menu-item.rad-grsetting a").addClass('active');
				$('.menu-dropdown.sett').addClass('show');
			}
			else if (current_page.match(/partner/)) {
				$("ul.nav-side li.pure-menu-item.rad-setting a").addClass('pure-menu-active');
				$("ul.menu-dropdown li.pure-menu-item.rad-gpsetting a").addClass('active');
				$('.menu-dropdown.sett').addClass('show');
			}
		}
		else if (current_page.match(/region/) || current_page.match(/coach_upcoming_session/) || current_page.match(/coach_histories/)){
			$("ul.nav-side li.pure-menu-item.rad-region a").addClass('pure-menu-active');
		}
		else if (current_page.match(/match_partner/)){
			$("ul.nav-side li.pure-menu-item.rad-pmatches a").addClass('pure-menu-active');
		}
		else if (current_page.match(/coach_script/)){
			$("ul.nav-side li.pure-menu-item.rad-cmaterials a").addClass('pure-menu-active');
		}
	}

	else if (current_page.match(/admin/)) {
		if (current_page.match(/manage_partner/) || current_page.match(/coach_upcoming_session/) || current_page.match(/partner_setting/) || current_page.match(/vrm/) || current_page.match(/coach_histories/) || current_page.match(/student_histories/) || current_page.match(/student_upcoming_session/)){
			$("ul.nav-side li.pure-menu-item.adm-partner a").addClass('pure-menu-active');
			if (current_page.match(/token/) || current_page.match(/history_token/)) {
				console.log('token approval');
				$("ul.nav-side li.pure-menu-item.adm-partner a").removeClass('pure-menu-active');
				$("ul.nav-side li.pure-menu-item.adm-token a").addClass('pure-menu-active');
				$("ul.menu-dropdown li.pure-menu-item.adm-tapproval a").addClass('active');
				$('.menu-dropdown').addClass('show');
			};
		}
		else if (current_page.match(/match_partner/)){
			$("ul.nav-side li.pure-menu-item.adm-pmatches a").addClass('pure-menu-active');
		}
		else if (current_page.match(/approve_user/)){
			$("ul.nav-side li.pure-menu-item.adm-capproval a").addClass('pure-menu-active');
		}
		else if (current_page.match(/token/)){
			console.log('coach approvals')
			$("ul.nav-side li.pure-menu-item.adm-token a").addClass('pure-menu-active');
			$("ul.menu-dropdown li.pure-menu-item.adm-rtoken a").addClass('active');
			$('.menu-dropdown').addClass('show');
		}
	}

	else if (current_page.match(/coach_vrm/)) {
		$("ul.nav-side li.pure-menu-item.cch-scd a").addClass('pure-menu-active');
		$("ul.menu-dropdown li.pure-menu-item.ses a").addClass('active');
		$('.menu-dropdown').addClass('show');
	}

	else if (current_page.match(/student_detail/)) {
		if (current_page.match(/coach/)){
			$("ul.nav-side li.pure-menu-item.cch-scd a").addClass('pure-menu-active');
			$("ul.menu-dropdown li.pure-menu-item.ses a").addClass('active');
			$('.menu-dropdown').addClass('show');
		}
	}

	else if (current_page.match(/student/)) {
		if (current_page.match(/dashboard/)) {
			$("ul.nav-side li.pure-menu-item.std-dashboard a").addClass('pure-menu-active');
		}
		else if (current_page.match(/find_coaches/)) {
			$("ul.nav-side li.pure-menu-item.std-book a").addClass('pure-menu-active');
		} 
		else if (current_page.match(/student_vrm/) || current_page.match(/certificate/)) {
			$("ul.nav-side li.pure-menu-item.std-sdashboard a").addClass('pure-menu-active');
		}	
		else if (current_page.match(/upcoming_session/) || current_page.match(/ongoing_session/) || current_page.match(/histories/) || current_page.match(/reschedule/) || current_page.match(/session/)) {
			console.log('sessions');
			$("ul.nav-side li.pure-menu-item.std-session a").addClass('pure-menu-active');
		}
		else if (current_page.match(/token/) || current_page.match(/token_requests/)) {
			console.log('tokens');
			$("ul.nav-side li.pure-menu-item.std-token a").addClass('pure-menu-active');
		}
		else if (current_page.match(/rate_coaches/)) {
			$("ul.nav-side li.pure-menu-item.std-rate a").addClass('pure-menu-active');
		}
		else if (current_page.match(/class_detail/)) {
			$("ul.nav-side li.pure-menu-item.std-class a").addClass('pure-menu-active');
		} 
	}

	else if (current_page.match(/partner/)) {
		if (current_page.match(/approve_coach_day_off/) || current_page.match(/history_coach_day_off/)) {
			$("ul.nav-side li.pure-menu-item.prt-doff a").addClass('pure-menu-active');
		}
		else if (current_page.match(/subgroup/) || current_page.match(/coach_upcoming_session/) || current_page.match(/coach_histories/)) {
			$("ul.nav-side li.pure-menu-item.prt-cgroup a").addClass('pure-menu-active');
		}
		else if (current_page.match(/reporting/)) {
			$("ul.nav-side li.pure-menu-item.prt-reporting a").addClass('pure-menu-active');
		}
	}

	else if (current_page.match(/coach/)) {
		if (current_page.match(/dashboard/) || current_page.match(/coach_material/) || current_page.match(/token/) || current_page.match(/token_withdrawals/)) {
			console.log('dashboard');
			$("ul.nav-side li.pure-menu-item.cch-dashboard a").addClass('pure-menu-active');

		}
		else if (current_page.match(/schedule/) || current_page.match(/day_off/)) {
			console.log('schedules');
			$("ul.nav-side li.pure-menu-item.cch-scd a").addClass('pure-menu-active');
			$("ul.menu-dropdown li.pure-menu-item.cch-sch-ins a").addClass('active');
			$('.menu-dropdown').addClass('show');
		}
		else if (current_page.match(/upcoming_session/) || current_page.match(/ongoing_session/) || current_page.match(/histories/)) {
			console.log('sessions');
			$("ul.nav-side li.pure-menu-item.cch-scd a").addClass('pure-menu-active');
			$("ul.menu-dropdown li.pure-menu-item.cch-ses a").addClass('active');
			$('.menu-dropdown').addClass('show');
		}
	}
});