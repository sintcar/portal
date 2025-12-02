CREATE TABLE IF NOT EXISTS update_tokens (
  id TEXT PRIMARY KEY,
  token TEXT NOT NULL,
  label TEXT,
  created_at TEXT NOT NULL,
  expires_at TEXT
);
