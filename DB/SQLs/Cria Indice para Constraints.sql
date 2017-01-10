select pg_index.indexrelid::regclass, 'create index ' || relname || '_' ||
         array_to_string(column_name_list, '_') || '_idx on ' || conrelid ||
         ' (' || array_to_string(column_name_list, ',') || ')'
from (select distinct
       conrelid,
       array_agg(attname) column_name_list,
       array_agg(attnum) as column_list
     from pg_attribute
          join (select conrelid::regclass,
                 conname,
                 unnest(conkey) as column_index
                from (select distinct
                        conrelid, conname, conkey
                      from pg_constraint
                        join pg_class on pg_class.oid = pg_constraint.conrelid
                        join pg_namespace on pg_namespace.oid = pg_class.relnamespace
                      where nspname !~ '^pg_' and nspname <> 'information_schema'
                      ) fkey
               ) fkey
               on fkey.conrelid = pg_attribute.attrelid
                  and fkey.column_index = pg_attribute.attnum
     group by conrelid, conname
     ) candidate_index
join pg_class on pg_class.oid = candidate_index.conrelid
left join pg_index on pg_index.indrelid = conrelid
                      and indkey::text = array_to_string(column_list, ' ')
where indexrelid is null


