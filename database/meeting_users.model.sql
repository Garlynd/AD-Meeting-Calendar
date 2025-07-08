CREATE TABLE IF NOT EXISTS meeting_users (
    meeting_id INT NOT NULL REFERENCES meeting(id),
    user_id INT NOT NULL REFERENCES users(id),
    role VARCHAR(50) NOT NULL,
    PRIMARY KEY (meeting_id, user_id)
);

COMMENT ON TABLE meeting_users IS 'Associates users to meetings with specific roles';
COMMENT ON COLUMN meeting_users.role IS 'User role in the meeting';