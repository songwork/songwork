createdb -T template0 -U songwork -O songwork -E UTF8 songwork
psql -X -U songwork -d songwork -f schema.pgsql 

createdb -T template0 -U songwork -O songwork -E UTF8 songwork_test
psql -X -U songwork -d songwork_test -f schema.pgsql 
psql -X -U songwork -d songwork_test -f fixtures.pgsql 
