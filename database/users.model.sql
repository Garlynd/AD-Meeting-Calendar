CREATE TABLE IF NOT EXISTS public."users" (
    id INT NOT NULL PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    create_time DATE DEFAULT CURRENT_DATE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100),
    role VARCHAR(50) NOT NULL
);

COMMENT ON TABLE public."users" IS 'Table storing user account and profile data';
COMMENT ON COLUMN public."users".username IS 'Unique username for login';
COMMENT ON COLUMN public."users".password IS 'Hashed password';
COMMENT ON COLUMN public."users".first_name IS 'User first name';
COMMENT ON COLUMN public."users".last_name IS 'User last name';
COMMENT ON COLUMN public."users".email IS 'Email address';
COMMENT ON COLUMN public."users".role IS 'User role (e.g., admin, member)';
