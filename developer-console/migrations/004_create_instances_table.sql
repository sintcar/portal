CREATE TABLE IF NOT EXISTS instances (
  id TEXT PRIMARY KEY,
  name TEXT NOT NULL,
  license_id TEXT,
  current_version TEXT,
  channel TEXT DEFAULT 'stable',
  last_seen_at TEXT,
  created_at TEXT NOT NULL,
  updated_at TEXT NOT NULL,
  FOREIGN KEY (license_id) REFERENCES licenses(id)
);
