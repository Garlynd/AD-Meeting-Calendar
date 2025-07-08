CREATE TABLE IF NOT EXISTS tasks (
    id INT NOT NULL PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    create_time DATE DEFAULT CURRENT_DATE,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    assigned_to INT REFERENCES users(id),
    meeting_id INT REFERENCES meeting(id),
    due_date DATE,
    status VARCHAR(50) DEFAULT 'Pending'
);

COMMENT ON TABLE tasks IS 'Task assignments related to meetings and users';
COMMENT ON COLUMN tasks.title IS 'Task title';
COMMENT ON COLUMN tasks.description IS 'Detailed description of the task';
COMMENT ON COLUMN tasks.assigned_to IS 'User assigned to the task';
COMMENT ON COLUMN tasks.meeting_id IS 'Related meeting';
COMMENT ON COLUMN tasks.due_date IS 'Task deadline';
COMMENT ON COLUMN tasks.status IS 'Current status of the task';