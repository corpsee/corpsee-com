CREATE TABLE "pages"
(
	"id"    SERIAL PRIMARY KEY,
	"alias" TEXT NOT NULL UNIQUE
);

CREATE TABLE "pages_content"
(
	"id"          SERIAL PRIMARY KEY,
	"language"    TEXT DEFAULT 'ru',
	"page_id"     INTEGER NOT NULL,
	"title"       TEXT DEFAULT '',
	"description" TEXT DEFAULT '',
	"keywords"    TEXT DEFAULT ''
);

CREATE TABLE "pictures"
(
	"id"          SERIAL PRIMARY KEY,
	"title"       TEXT NOT NULL UNIQUE,
	"image"       TEXT NOT NULL UNIQUE,
	"description" TEXT DEFAULT '',
	"create_date" TIMESTAMP WITH TIME ZONE,
	"post_date"   TIMESTAMP WITH TIME ZONE,
	"modify_date" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "tags"
(
	"id"          SERIAL PRIMARY KEY,
	"tag"         TEXT NOT NULL UNIQUE,
	"post_date"   TIMESTAMP WITH TIME ZONE,
	"modify_date" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "pictures_tags"
(
	"id"          SERIAL PRIMARY KEY,
	"picture_id"  INTEGER NOT NULL,
	"tag_id"      INTEGER NOT NULL
);

CREATE TABLE "last_modify"
(
	"id"          SERIAL PRIMARY KEY,
	"table"       TEXT NOT NULL UNIQUE,
	"modify_date" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "pull_requests"
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