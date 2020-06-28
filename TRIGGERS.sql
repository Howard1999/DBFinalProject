delimiter &&

create trigger login_time_update before update on user
	for each row
		begin
			if !(new.session_id <=> old.session_id) and !(new.session_id <=> NULL)
			then
				set new.last_login_time=old.login_time;
				set new.login_time=current_timestamp;
			end if;
		end&&

create trigger last_edit_time_update before update on article
	for each row
		begin
			set new.last_edit_time=current_timestamp;
		end&&

delimiter ;
