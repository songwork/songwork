BEGIN;

-- hashpass = same as email before @
INSERT INTO persons(id, email, hashpass, lopass, name, country) VALUES (1, 'songwork@songwork.com', '$2a$08$GGfqP8IM8NHdzinGzM7i7uhysyWGmFnWlILbHePmi5oqoBZiOsKKS', 'abcd', 'Songwork', 'US');
INSERT INTO persons(id, email, hashpass, lopass, name, country) VALUES (2, 'pepe@lepew.fr', '$2a$08$WghqwLVCRWCvUDVPha0aNuoucvAwudQWslljsRiXNiwZhDwrMYGEm', 'dcba', 'Pepé Le Pew', 'FR');
INSERT INTO persons(id, email, hashpass, lopass, name, country) VALUES (3, 'yoko@yahoo.co.jp', '$2a$08$wNwO8KXRUV3wBlrxUnIdrOXvGU.RD7fZS/KXAbURP0f2y0ooelbJS', 'lmno', 'Yoko Ono', 'JP');
INSERT INTO persons(id, email, hashpass, lopass, name, country) VALUES (4, 'tiny@tim.com', '$2a$08$Hm5fRBh7ULUMsLhTuGbWDO7ew2zYZ9jltSeMErTdaOjcJT9WBPaFC', 'zyxw', 'Tiny Tim', 'UK');
INSERT INTO persons(id, email, hashpass, lopass, name, country, categorize_as) VALUES (5, 'wonka@chocolate.com', '$2a$08$e.s2SLbuOQcA1XhC6X17p.LVC3WvPOuX2OZwZPAblq5awOuyKnHvC', 'oomp', 'Willy Wonka', 'US', 'wonka');
INSERT INTO persons(id, email, hashpass, lopass, name, country, categorize_as) VALUES (6, 'augustus@gloop.com', '$2a$08$NN3e.a1bulZdVd3xTmny5Ol6IfabghpTF6..TY3lfOvX/W8ZzG9w2', 'owpa', 'Augustus Gloop', 'DE', 'wonka');
INSERT INTO persons(id, email, hashpass, lopass, name, country, categorize_as) VALUES (7, 'veruca@salt.com', '$2a$08$QftqVe4qU/HhHD92DMNnmO2lZNPiRZzs/QWdsKyefNpS9f2Tgo8CW', 'iwin', 'Veruca Salt', 'UK', 'wonka');

INSERT INTO logins(cookie_id, domain, person_id, cookie_tok, cookie_exp, last_login, ip) VALUES ('47efc34c4c0cc793baceefa556dca2c8', 'songwork.com', 1, '3ad1344195e57ab27eb333e1d6f8a659', 1249393574, '2010-01-13', '4.2.2.4');
INSERT INTO logins(cookie_id, domain, person_id, cookie_tok, cookie_exp, last_login, ip) VALUES ('6c35c973b4af1dfb50fc06eb6d9dde8d', 'songwork.com', 2, '3f56411729a9220e812f3ce2fcc74ac4', 1249393575, '2010-01-29', '8.8.8.8');

INSERT INTO students (id, person_id) VALUES (1, 6);
INSERT INTO students (id, person_id) VALUES (2, 7);

INSERT INTO teachers (id, person_id, available) VALUES (1, 4, 'f');
INSERT INTO teachers (id, person_id, available) VALUES (2, 5, 't');

INSERT INTO admins (id, person_id) VALUES (1, 1);

INSERT INTO pricerefs (id, code, currency, millicents) VALUES (1, 'a', 'USD', 10000);
INSERT INTO pricerefs (id, code, currency, millicents) VALUES (2, 'a', 'GBP', 5000);
INSERT INTO pricerefs (id, code, currency, millicents) VALUES (3, 'a', 'EUR', 7000);
INSERT INTO pricerefs (id, code, currency, millicents) VALUES (4, 'a', 'JPY', 80000);
INSERT INTO pricerefs (id, code, currency, millicents) VALUES (5, 'b', 'USD', 20000);
INSERT INTO pricerefs (id, code, currency, millicents) VALUES (6, 'b', 'GBP', 10000);
INSERT INTO pricerefs (id, code, currency, millicents) VALUES (7, 'b', 'EUR', 15000);
INSERT INTO pricerefs (id, code, currency, millicents) VALUES (8, 'b', 'JPY', 150000);

INSERT INTO documents (id, name, created_at, added_at, removed_at, bytes, mediatype, url, description, pricecode) VALUES (1, 'Songwriting Tips', '2010-01-01', '2010-01-02', NULL, 688008, 'pdf', 'Willy_Wonka-Songwriting_Tips.pdf', 'Songwriting tips from Willy Wonka himself.', 'a');
INSERT INTO documents (id, name, created_at, added_at, removed_at, bytes, mediatype, url, description, pricecode) VALUES (2, 'Rhyming Dictionary', '2010-01-03', '2010-01-04', NULL, 251545, 'doc', 'Willy_Wonka-Rhyming_Dictionary.doc', 'Rhyming dictionary from Willy Wonka.', 'b');
INSERT INTO documents (id, name, created_at, added_at, removed_at, bytes, mediatype, url, description, pricecode) VALUES (3, 'Watch Me Sing', '2010-01-05', '2010-01-06', NULL, 383540139, 'mov', 'Tiny_Tim-Watch_Me_Sing.mov', 'Watch Tim sing in this exciting new video.', 'b');

INSERT INTO documents_teachers (document_id, teacher_id) VALUES (1, 2);
INSERT INTO documents_teachers (document_id, teacher_id) VALUES (2, 2);
INSERT INTO documents_teachers (document_id, teacher_id) VALUES (3, 1);

INSERT INTO tags (id, name) VALUES (1, 'words');
INSERT INTO tags (id, name) VALUES (2, 'harmony');
INSERT INTO tags (id, name) VALUES (3, 'vocals');

INSERT INTO documents_tags (document_id, tag_id) VALUES (1, 1);
INSERT INTO documents_tags (document_id, tag_id) VALUES (1, 2);
INSERT INTO documents_tags (document_id, tag_id) VALUES (2, 1);
INSERT INTO documents_tags (document_id, tag_id) VALUES (3, 1);
INSERT INTO documents_tags (document_id, tag_id) VALUES (3, 3);

INSERT INTO consultations (id, starts_at, instructions, currency, millicents, done) VALUES (1, '2010-05-05 13:00:00 PST', 'Augustus, call Willy', 'USD', 1000000, 't');
INSERT INTO consultations_students (consultation_id, student_id) VALUES (1, 1);
INSERT INTO consultations_teachers (consultation_id, teacher_id) VALUES (1, 2);
INSERT INTO consultations (id, starts_at, instructions, currency, millicents, done) VALUES (2, '2010-06-01 10:00:00 EST', 'Veruca, call Willy', 'USD', 1000000, 'f');
INSERT INTO consultations_students (consultation_id, student_id) VALUES (2, 2);
INSERT INTO consultations_teachers (consultation_id, teacher_id) VALUES (2, 2);

INSERT INTO consultation_requests (id, student_id, teacher_id, consultation_id, created_at, answered_at, closed_at, student_notes, teacher_notes) VALUES (1, 1, 2, 1, '2010-05-01', '2010-05-02', '2010-05-02', 'May I?', 'Yes. In a few days.');
INSERT INTO consultation_requests (id, student_id, teacher_id, consultation_id, created_at, answered_at, closed_at, student_notes, teacher_notes) VALUES (2, 2, 2, 2, '2010-05-10', '2010-05-11', NULL, 'May I?', 'Yes. In a few weeks.');
INSERT INTO consultation_requests (id, student_id, teacher_id, consultation_id, created_at, answered_at, closed_at, student_notes, teacher_notes) VALUES (3, 1, 2, NULL, '2010-05-15', NULL, NULL, 'May I have another?', NULL);

INSERT INTO payments (id, student_id, document_id, currency, millicents, details, created_at) VALUES (1, 2, 3, 'GBP', 10000, 'PayPal transaction ID#: 1313a878bf', NOW());
INSERT INTO payments (id, student_id, document_id, currency, millicents, details, created_at) VALUES (2, 2, 2, 'GBP', 10000, 'PayPal transaction ID#: xx13a878bf', NOW());
INSERT INTO payments (id, student_id, document_id, currency, millicents, details, created_at) VALUES (3, 2, 1, 'GBP', 5000, 'PayPal transaction ID#: xx13a878bg', NOW());
INSERT INTO payments (id, student_id, document_id, currency, millicents, details, created_at) VALUES (4, 1, 1, 'EUR', 15000, 'PayPal transaction ID#: whatever', NOW());
INSERT INTO payments (id, student_id, consultation_id, currency, millicents, details, created_at) VALUES (5, 1, 1, 'USD', 1000000, 'PayPal transaction ID#: blah', NOW());

INSERT INTO paypaltxns (id, payment_id, created_at, txn_id, txn_type, info, reconciled) VALUES (1, 1, NOW(), '1313a878bf', 'web_accept', 'transaction_subject = Watch Me Sing
payment_date = 09:49:46 Sep 12, 2009 PDT
txn_type = web_accept
first_name = Veruca
last_name = Salt
residence_country = UK
item_name = Watch Me Sing
mc_currency = GBP
mc_gross = 10.00
business = songwork@songwork.com
payment_type = instant
payer_status = verified
payer_email = veruca@salt.com
txn_id = 1313a878bf
quantity = 1
receiver_email = songwork@songwork.com
payer_id = 7F6YYD8DKZE4Q
receiver_id = XX7UP87AEABXW
item_number = 3
custom = PERSON_ID=7 STUDENT_ID=2 TEACHER_ID=false
payment_status = Completed', 't');
INSERT INTO paypaltxns (id, payment_id, created_at, txn_id, txn_type, info, reconciled) VALUES (2, 2, NOW(), 'xx13a878bf', 'web_accept', 'transaction_subject = Rhyming Dictionary
payment_date = 09:49:49 Sep 12, 2009 PDT
txn_type = web_accept
first_name = Veruca
last_name = Salt
residence_country = UK
item_name = Rhyming Dictionary
mc_currency = GBP
mc_gross = 10.00
business = songwork@songwork.com
payment_type = instant
payer_status = verified
payer_email = veruca@salt.com
txn_id = xx13a878bf
quantity = 1
receiver_email = songwork@songwork.com
payer_id = 7F6YYD8DKZE4Q
receiver_id = XX7UP87AEABXW
item_number = 2
custom = PERSON_ID=7 STUDENT_ID=2 TEACHER_ID=false
payment_status = Completed', 't');
INSERT INTO paypaltxns (id, payment_id, created_at, txn_id, txn_type, info, reconciled) VALUES (3, 3, NOW(), 'xx13a878bg', 'web_accept', 'transaction_subject = Songwriting Tips
payment_date = 09:49:51 Sep 12, 2009 PDT
txn_type = web_accept
first_name = Veruca
last_name = Salt
residence_country = UK
item_name = Songwriting Tips
mc_currency = GBP
mc_gross = 5.00
business = songwork@songwork.com
payment_type = instant
payer_status = verified
payer_email = veruca@salt.com
txn_id = xx13a878bg
quantity = 1
receiver_email = songwork@songwork.com
payer_id = 7F6YYD8DKZE4Q
receiver_id = XX7UP87AEABXW
item_number = 1
custom = PERSON_ID=7 STUDENT_ID=2 TEACHER_ID=false
payment_status = Completed', 't');
INSERT INTO paypaltxns (id, payment_id, created_at, txn_id, txn_type, info, reconciled) VALUES (4, NULL, NOW(), 'whatever', 'web_accept', 'transaction_subject = Songwriting Tips
payment_date = 09:49:49 Sep 14, 2009 PDT
txn_type = web_accept
first_name = Augustus
last_name = Gloop
residence_country = DE
item_name = Songwriting Tips
mc_currency = EUR
mc_gross = 1.50
business = songwork@songwork.com
payment_type = instant
payer_status = verified
payer_email = augustus@gloop.com
txn_id = whatever
quantity = 1
receiver_email = songwork@songwork.com
payer_id = 9F6YYD8DKZE4Q
receiver_id = XX7UP87AEABXW
item_number = 1
custom = PERSON_ID=6 STUDENT_ID=1 TEACHER_ID=false
payment_status = Completed', 'f');
INSERT INTO paypaltxns (id, payment_id, created_at, txn_id, txn_type, info, reconciled) VALUES (5, 5, NOW(), 'blah', 'web_accept', 'transaction_subject = Consultation with Willy Wonka
payment_date = 09:49:49 Sep 14, 2009 PDT
txn_type = web_accept
first_name = Augustus
last_name = Gloop
residence_country = DE
item_name = Consultation
mc_currency = USD
mc_gross = 100.00
business = songwork@songwork.com
payment_type = instant
payer_status = verified
payer_email = augustus@gloop.com
txn_id = whatever
quantity = 1
receiver_email = songwork@songwork.com
payer_id = 9F6YYD8DKZE4Q
receiver_id = XX7UP87AEABXW
item_number = 1
custom = PERSON_ID=6 STUDENT_ID=1 TEACHER_ID=false
payment_status = Completed', 't');


COMMIT;

BEGIN;
SELECT pg_catalog.setval('persons_id_seq', (SELECT MAX(id) FROM persons) + 1, false);
SELECT pg_catalog.setval('students_id_seq', (SELECT MAX(id) FROM students) + 1, false);
SELECT pg_catalog.setval('teachers_id_seq', (SELECT MAX(id) FROM teachers) + 1, false);
SELECT pg_catalog.setval('admins_id_seq', (SELECT MAX(id) FROM admins) + 1, false);
SELECT pg_catalog.setval('pricerefs_id_seq', (SELECT MAX(id) FROM pricerefs) + 1, false);
SELECT pg_catalog.setval('documents_id_seq', (SELECT MAX(id) FROM documents) + 1, false);
SELECT pg_catalog.setval('payments_id_seq', (SELECT MAX(id) FROM payments) + 1, false);
SELECT pg_catalog.setval('paypaltxns_id_seq', (SELECT MAX(id) FROM paypaltxns) + 1, false);
SELECT pg_catalog.setval('tags_id_seq', (SELECT MAX(id) FROM tags) + 1, false);
SELECT pg_catalog.setval('consultations_id_seq', (SELECT MAX(id) FROM consultations) + 1, false);
SELECT pg_catalog.setval('consultation_requests_id_seq', (SELECT MAX(id) FROM consultation_requests) + 1, false);
COMMIT;

BEGIN;

