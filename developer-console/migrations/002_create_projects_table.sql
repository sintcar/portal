CREATE TABLE IF NOT EXISTS releases (
  id TEXT PRIMARY KEY,
  version TEXT NOT NULL,
  channel TEXT NOT NULL,
  url TEXT NOT NULL,
  checksum TEXT,
  notes TEXT,
  released_at TEXT NOT NULL,
  is_latest INTEGER NOT NULL DEFAULT 0
);
