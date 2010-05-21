BEGIN;

CREATE OR REPLACE FUNCTION crypt(text, text) RETURNS text AS '$libdir/pgcrypto', 'pg_crypt' LANGUAGE c IMMUTABLE STRICT;
CREATE OR REPLACE FUNCTION gen_salt(text) RETURNS text AS '$libdir/pgcrypto', 'pg_gen_salt' LANGUAGE c STRICT;
CREATE OR REPLACE FUNCTION gen_salt(text, integer) RETURNS text AS '$libdir/pgcrypto', 'pg_gen_salt_rounds' LANGUAGE c STRICT;

CREATE SCHEMA songwork;
SET search_path = songwork;

CREATE TABLE persons (
	id serial primary key,
	email varchar(127) UNIQUE,
	hashpass char(72),
	lopass char(4),
	newpass char(8) UNIQUE,
	name varchar(127),
	fullname varchar(127),
	address varchar(64),
	city varchar(24),
	state varchar(16),
	postalcode varchar(12),
	country char(2),
	phone varchar(18),
	changes text,
	notes text,
	confirmed integer,
	listype varchar(4),
	ip varchar(15),
	categorize_as varchar(16),
	created_at date not null default CURRENT_DATE
);
CREATE INDEX person_name ON persons(name);

CREATE TABLE logins (
	cookie_id char(32) not null,
	cookie_tok char(32) not null,
	cookie_exp integer not null,
	domain varchar(32) not null,
	person_id integer not null REFERENCES persons(id),
	last_login date not null default CURRENT_DATE,
	ip varchar(15),
	PRIMARY KEY (cookie_id, cookie_tok)
);
CREATE INDEX logins_person_id ON logins(person_id);

CREATE TABLE students (
	id serial primary key,
	person_id integer not null UNIQUE REFERENCES persons(id),
	created_at date DEFAULT CURRENT_DATE,
	their_notes text,
	our_notes text
);

CREATE TABLE teachers (
	id serial primary key,
	person_id integer not null UNIQUE REFERENCES persons(id),
	created_at date DEFAULT CURRENT_DATE,
	profile text,
	available BOOLEAN not null default FALSE,
	consultation_rate text
);

CREATE TABLE admins (
	id serial primary key,
	person_id integer not null UNIQUE REFERENCES persons(id)
);

CREATE TABLE pricerefs (
	id serial primary key,
	code char(1) not null,
	currency char(3) not null,
	millicents integer not null
);
CREATE INDEX pricecode ON pricerefs(code);

CREATE TABLE documents (
	id serial primary key,
	created_at date DEFAULT CURRENT_DATE,
	added_at date,
	removed_at date,
	name varchar(127),
	bytes integer,
	mediatype varchar(32),
	url varchar(127),
	youtube varchar(16), -- YouTube code for preview. example: GNMalhr2lUI
	length varchar(24), -- example: "32 minutes"
	sentence text,
	description text,
	pricecode char(1)
);
CREATE INDEX docaa ON documents(added_at);
CREATE INDEX docra ON documents(removed_at);

CREATE TABLE documents_teachers (
	document_id integer not null REFERENCES documents(id),
	teacher_id integer not null REFERENCES teachers(id),
	primary key (document_id, teacher_id)
);
CREATE INDEX d_tdi ON documents_teachers(document_id);
CREATE INDEX d_tti ON documents_teachers(teacher_id);

CREATE TABLE tags (
	id serial primary key,
	name varchar(32) unique
);

CREATE TABLE documents_tags (
	document_id integer not null REFERENCES documents(id),
	tag_id integer not null REFERENCES tags(id),
	primary key (document_id, tag_id)
);
CREATE INDEX d_gdi ON documents_tags(document_id);
CREATE INDEX d_gti ON documents_tags(tag_id);

CREATE TABLE consultations (
	id serial primary key,
	starts_at timestamp(0) with time zone,
	instructions text,
	currency char(3),
	millicents integer,
	student_notes text,
	teacher_notes text,
	done boolean not null default false
);

CREATE TABLE consultation_requests (
	id serial primary key,
	student_id	integer not null references students(id),
	teacher_id	integer not null references teachers(id),
	consultation_id	integer references consultations(id),  -- if it turns into a consultation
	created_at date,
	answered_at date,
	closed_at date,
	student_notes text,
	teacher_notes text
);

CREATE TABLE consultations_students (
	consultation_id	integer not null references consultations(id),
	student_id	integer not null references students(id),
	PRIMARY KEY (consultation_id, student_id)
);
CREATE INDEX csci ON consultations_students(consultation_id);
CREATE INDEX cssi ON consultations_students(student_id);

CREATE TABLE consultations_teachers (
	consultation_id	integer not null references consultations(id),
	teacher_id	integer not null references teachers(id),
	PRIMARY KEY (consultation_id, teacher_id)
);
CREATE INDEX ctci ON consultations_teachers(consultation_id);
CREATE INDEX ctti ON consultations_teachers(teacher_id);

CREATE TABLE payments (
	id serial primary key,
	student_id integer not null REFERENCES students(id),
	document_id integer REFERENCES documents(id),
	consultation_id integer REFERENCES consultations(id),
	currency char(3) not null default 'USD',
	millicents integer,
	details text,
	created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX pypi ON payments(student_id);
CREATE INDEX pydi ON payments(document_id);

CREATE TABLE paypaltxns (
	id serial primary key,
	payment_id integer REFERENCES payments(id),
	created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP,
	txn_id varchar(32),
	txn_type varchar(35),
	info text,
        reconciled boolean not null default false
);
CREATE INDEX txpi ON paypaltxns(payment_id);
CREATE INDEX txrc ON paypaltxns(reconciled);

COMMIT;

