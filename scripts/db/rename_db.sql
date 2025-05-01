SELECT pg_terminate_backend(pg_stat_activity.pid)
FROM pg_stat_activity
WHERE pg_stat_activity.datname = :OLD_DB_NAME -- ← change this to your DB
  AND pid <> pg_backend_pid();
ALTER DATABASE :OLD_DB_NAME RENAME TO :NEW_DB_NAME;
CREATE DATABASE :OLD_DB_NAME;