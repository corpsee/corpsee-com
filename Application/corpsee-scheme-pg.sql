CREATE TABLE "pages"
(
	"id"    SERIAL PRIMARY KEY,
	"alias" VARCHAR(150) NOT NULL UNIQUE
);

CREATE TABLE "pages_content"
(
	"id"          SERIAL PRIMARY KEY,
	"language"    VARCHAR(5) DEFAULT 'ru',
	"page_id"     INT NOT NULL REFERENCES "pages" ("id") ON DELETE CASCADE,
	"title"       TEXT DEFAULT '',
	"description" TEXT DEFAULT '',
	"keywords"    TEXT DEFAULT ''
);

CREATE TABLE "pictures"
(
	"id"          SERIAL PRIMARY KEY,
	"title"       VARCHAR(150) NOT NULL UNIQUE,
	"image"       VARCHAR(150) NOT NULL UNIQUE,
	"description" TEXT DEFAULT '',
	"create_date" TIMESTAMP,
	"post_date"   TIMESTAMP,
	"modify_date" TIMESTAMP
);

CREATE TABLE "sites"
(
	"id"          SERIAL PRIMARY KEY,
	"title"       VARCHAR(150) NOT NULL UNIQUE,
	"image"       VARCHAR(150) NOT NULL UNIQUE,
	"description" TEXT DEFAULT '',
	"create_date" TIMESTAMP,
	"post_date"   TIMESTAMP,
	"modify_date" TIMESTAMP
);

CREATE TABLE "tags"
(
	"id"          SERIAL PRIMARY KEY,
	"tag"         VARCHAR(150) NOT NULL UNIQUE,
	"post_date"   TIMESTAMP,
	"modify_date" TIMESTAMP
);

CREATE TABLE "pictures_tags"
(
	"id"          SERIAL PRIMARY KEY,
	"pictures_id" INT NOT NULL REFERENCES "pictures" ("id") ON DELETE CASCADE,
	"tags_id"     INT NOT NULL REFERENCES "tags" ("id") ON DELETE CASCADE
);

CREATE TABLE "last_modify"
(
	"id"          SERIAL PRIMARY KEY,
	"table"       VARCHAR(150) NOT NULL UNIQUE,
	"modify_date" TIMESTAMP
);

CREATE TABLE "pull_requests"
(
	"id"          SERIAL PRIMARY KEY,
	"repository"  VARCHAR(150) NOT NULL,
	"number"      INT NOT NULL,
	"body"        TEXT DEFAULT '',
	"title"       TEXT DEFAULT '',
	"status"      VARCHAR(10) NOT NULL,
	"commits"     INT NOT NULL,
	"additions"   INT NOT NULL,
	"deletions"   INT NOT NULL,
	"files"       INT NOT NULL,
	"create_date" TIMESTAMP
);