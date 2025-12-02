CREATE TABLE IF NOT EXISTS update_jobs (
  id TEXT PRIMARY KEY,
  instance_id TEXT NOT NULL,
  release_id TEXT NOT NULL,
  status TEXT NOT NULL DEFAULT 'pending',
  strategy TEXT DEFAULT 'rolling',
  started_at TEXT,
  completed_at TEXT,
  created_at TEXT NOT NULL,
  updated_at TEXT NOT NULL,
  FOREIGN KEY (instance_id) REFERENCES instances(id),
  FOREIGN KEY (release_id) REFERENCES releases(id)
);
