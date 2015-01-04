CREATE TABLE "tbl_pages"
(
	"id"    SERIAL PRIMARY KEY,
	"alias" TEXT NOT NULL UNIQUE
);

CREATE TABLE "tbl_pages_content"
(
	"id"          SERIAL PRIMARY KEY,
	"language"    TEXT DEFAULT 'ru',
	"page_id"     INTEGER NOT NULL,
	"title"       TEXT DEFAULT '',
	"description" TEXT DEFAULT '',
	"keywords"    TEXT DEFAULT ''
);

CREATE TABLE "tbl_pictures"
(
	"id"          SERIAL PRIMARY KEY,
	"title"       TEXT NOT NULL UNIQUE,
	"image"       TEXT NOT NULL UNIQUE,
	"description" TEXT DEFAULT '',
	"create_date" TIMESTAMP WITH TIME ZONE,
	"post_date"   TIMESTAMP WITH TIME ZONE,
	"modify_date" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "tbl_projects"
(
	"id"          SERIAL PRIMARY KEY,
	"title"       TEXT NOT NULL UNIQUE,
	"image"       TEXT NOT NULL UNIQUE,
	"description" TEXT DEFAULT '',
	"create_date" TIMESTAMP WITH TIME ZONE,
	"post_date"   TIMESTAMP WITH TIME ZONE,
	"modify_date" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "tbl_tags"
(
	"id"          SERIAL PRIMARY KEY,
	"tag"         TEXT NOT NULL UNIQUE,
	"post_date"   TIMESTAMP WITH TIME ZONE,
	"modify_date" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "tbl_pictures_tags"
(
	"id"          SERIAL PRIMARY KEY,
	"pictures_id" INTEGER NOT NULL,
	"tags_id"     INTEGER NOT NULL
);

CREATE TABLE "tbl_last_modify"
(
	"id"          SERIAL PRIMARY KEY,
	"table"       TEXT NOT NULL UNIQUE,
	"modify_date" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "tbl_pull_requests"
(
	"id"          SERIAL PRIMARY KEY,
	"repository"  TEXT NOT NULL,
	"number"      INTEGER NOT NULL,
	"title"       TEXT DEFAULT '',
	"body"        TEXT DEFAULT '',
	"status"      TEXT NOT NULL,
	"commits"     INTEGER NOT NULL,
	"additions"   INTEGER NOT NULL,
	"deletions"   INTEGER NOT NULL,
	"files"       INTEGER NOT NULL,
	"create_date" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "tbl_posts"
(
	"id"          SERIAL PRIMARY KEY,
	"title"       TEXT NOT NULL UNIQUE,
	"content"     TEXT DEFAULT '',
	"post_date"   TIMESTAMP WITHOUT TIME ZONE,
	"modify_date" TIMESTAMP WITHOUT TIME ZONE
);