# Module Tracking
Stats des informations de tracking

Nettoyage de la colonne tracer
```
UPDATE ps_customer SET tracer = REPLACE(REPLACE(REPLACE(tracer, " ", ""), "\n", ""), "\r", "") 
WHERE date_add BETWEEN "2017-02-01 00:00:00" AND "2017-02-28 23:59:59"

SELECT tracer FROM `ps_customer` WHERE date_add BETWEEN "2017-02-01 00:00:00" AND "2017-02-01 23:59:59"
```
