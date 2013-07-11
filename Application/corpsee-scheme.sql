CREATE TABLE `tbl_pages` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`name` TEXT NOT NULL UNIQUE,
	`title` TEXT DEFAULT (''),
	`description` TEXT DEFAULT (''),
	`keywords` TEXT DEFAULT ('')
);

CREATE TABLE `tbl_pictures` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`title` TEXT NOT NULL UNIQUE,
	`image` TEXT NOT NULL UNIQUE,
	`description` TEXT DEFAULT (''),
	`create_date` INTEGER DEFAULT(0),
	`post_date` INTEGER DEFAULT(0),
	`modify_date` INTEGER DEFAULT(0)
);

CREATE TABLE `tbl_sites` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`title` TEXT NOT NULL UNIQUE,
	`image` TEXT NOT NULL UNIQUE,
	`description` TEXT DEFAULT (''),
	`create_date` INTEGER DEFAULT(0),
	`post_date` INTEGER DEFAULT(0),
	`modify_date` INTEGER DEFAULT(0)
);

CREATE TABLE `tbl_tags` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`tag` TEXT NOT NULL UNIQUE,
	`post_date` INTEGER DEFAULT(0),
	`modify_date` INTEGER DEFAULT(0)
);

CREATE TABLE `tbl_pictures_tags`
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`pictures_id` INTEGER NOT NULL,
	`tags_id` INTEGER NOT NULL
);

CREATE TABLE `tbl_last_modify` 
(
	`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
	`table` TEXT NOT NULL UNIQUE,
	`modify_date` INTEGER DEFAULT(0)
);