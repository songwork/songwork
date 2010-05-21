psql -q -X -U songwork -d songwork_test -f schema-drop.pgsql 2>/dev/null
psql -q -X -U songwork -d songwork_test -f schema.pgsql 2>/dev/null
psql -q -X -U songwork -d songwork_test -f fixtures.pgsql
