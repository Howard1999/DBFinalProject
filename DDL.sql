use team1;

create table board(
	board_name varchar(20)not null,
	popularity int unsigned default 0,
	primary key(board_name)
)ENGINE=INNODB;

create table user(
	user_name varchar(20)not null unique,
	account varchar(20)not null,
	password varchar(20)not null,
	user_authority char(1)not null check(level in ("A","B","C")),
	login_time datetime,
	last_login_time datetime,
	board_name varchar(20)default null,
	login_ip varchar(15),
	last_login_ip varchar(15),
	session_id varchar(100),
	primary key(account),
	foreign key(board_name)references board(board_name)on delete set null on update cascade
)ENGINE=INNODB;

create table artical_building(
	building_ID int AUTO_INCREMENT,
	board_name varchar(20)not null,
	account varchar(20)not null,
	title varchar(50)not null default "Untitled",
	create_time datetime,
	primary key(building_ID),
	foreign key(board_name)references board(board_name)on delete cascade on update cascade,
	foreign key(account)references user(account)on delete cascade
)ENGINE=INNODB;

create table artical(
	artical_ID int AUTO_INCREMENT,
	building_ID int not null,
	account varchar(20)not null,
	content text,
	last_edit_time datetime,
	primary key(artical_ID),
	foreign key(building_ID)references artical_building(building_ID)on delete cascade,
	foreign key(account)references user(account)on delete cascade
)ENGINE=INNODB;

create table comment(
	account varchar(20)not null,
	artical_ID int not null,
	content varchar(100)not null,
	post_time datetime,
	primary key(account, post_time),
	foreign key(account)references user(account)on delete cascade,
	foreign key(artical_ID)references artical(artical_ID)on delete cascade
)ENGINE=INNODB;

create table like_dislike(
	account varchar(20)not null,
	artical_ID int not null,
	type boolean not null,
	primary key(account, artical_ID),
	foreign key(account)references user(account)on delete cascade,
	foreign key(artical_ID)references artical(artical_ID)on delete cascade
)ENGINE=INNODB;

create table ban_account(
	board_name varchar(20)not null,
	account varchar(20)not null,
	level char(1)not null check(level in ("A","B","C")),
	start_time datetime not null,
	end_time datetime not null,
	primary key(board_name, account),
	foreign key(account)references user(account)on delete cascade,
	foreign key(board_name)references board(board_name)on delete cascade on update cascade
)ENGINE=INNODB;

--drop table ban_account, like_dislike, comment, artical, artical_building, user, board;