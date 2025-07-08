CREATE TABLE IF NOT EXISTS projects (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    name varchar(255) NOT NULL,
    description text,
    start_date date,
    end_date date
);