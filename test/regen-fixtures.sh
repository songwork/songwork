# create NEW test fixtures 
dropdb songwork_test
createdb -T template0 -O songwork -E UTF8 songwork_test
psql -q -X -U songwork -d songwork_test -f schema.pgsql 
psql -q -X -U songwork -d songwork_test -f fixtures.pgsql 
