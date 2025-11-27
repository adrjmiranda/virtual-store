CREATE TYPE event_type AS ENUM ('login', 'logout', 'create', 'update', 'delete', 'payment', 'system');

CREATE TABLE IF NOT EXISTS event_logs (
  id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  user_id BIGINT NULL REFERENCES users(id),
  type event_type NOT NULL,
  description TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT now(),
  updated_at TIMESTAMP NOT NULL DEFAULT now()
);

CREATE OR REPLACE FUNCTION update_timestamp()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = now();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_event_logs_updated_at
BEFORE UPDATE ON event_logs
FOR EACH ROW
EXECUTE FUNCTION update_timestamp();