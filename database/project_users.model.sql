CREATE TABLE IF NOT EXISTS project_users (
    project_id INTEGER NOT NULL REFERENCES projects (id),
    user_id INTEGER NOT NULL REFERENCES users (id),
    PRIMARY KEY (project_id, user_id)
);