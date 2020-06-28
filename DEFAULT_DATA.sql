use team2;

insert into board values("場外休憩版",0);
insert into board values("被刪掉的版",0);
insert into board values("沒被刪掉的版",0);

insert into user values("管理員","admin","password","A",NULL,NULL,NULL,NULL,NULL,NULL);
insert into user values("user","user","password","C",NULL,NULL,NULL,NULL,NULL,NULL);

insert into article_building values(default,"場外休憩版","user","第一棟樓",current_timestamp);
insert into article_building values(default,"場外休憩版","admin","Second Floor",current_timestamp);
insert into article_building values(default,"被刪掉的版","user","這裡沒東西",current_timestamp);

insert into article values(default,1,"user","Pneumonoultramicroscopicsilicovolcanoconiosis 火山矽肺病",current_timestamp);
insert into article values(default,1,"admin","What's wrong with you",current_timestamp);
insert into article values(default,2,"admin","Test",current_timestamp);
insert into article values(default,3,"user","就說沒東西了",current_timestamp);

insert into comment values("user",2,"同意",current_timestamp);
insert into comment values("admin",3,"星爆爆",current_timestamp);
insert into comment values("user",4,"奇怪ㄟ你",current_timestamp);
