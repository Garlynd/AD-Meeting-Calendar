CREATE TABLE IF NOT EXISTS tasks (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    project_id uuid NOT NULL REFERENCES projects (id) ON DELETE CASCADE,
    assigned_to uuid REFERENCES users (id) ON DELETE SET NULL,
    title varchar(255) NOT NULL,
    description text,
    status varchar(50) DEFAULT 'pending',
    due_date date
);