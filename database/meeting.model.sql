CREATE TABLE IF NOT EXISTS meeting (
    id INT NOT NULL PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    create_time DATE DEFAULT CURRENT_DATE,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    date TIMESTAMP NOT NULL,
    location VARCHAR(100),
    created_by INT REFERENCES users(id)
);

COMMENT ON TABLE meeting IS 'Table storing meeting details';
COMMENT ON COLUMN meeting.title IS 'Meeting title';
COMMENT ON COLUMN meeting.description IS 'Details about the meeting';
COMMENT ON COLUMN meeting.date IS 'Scheduled date and time';
COMMENT ON COLUMN meeting.location IS 'Physical or virtual location';
COMMENT ON COLUMN meeting.created_by IS 'User who created the meeting';