const path = require('path');
const fs = require('fs');
const sqlite3 = require('sqlite3').verbose();

const DB_PATH = path.join(__dirname, '../../developer-console.sqlite');
let dbInstance;

function runMigrations(db) {
  const migrationsDir = path.join(__dirname, '../../migrations');
  const migrationFiles = fs
    .readdirSync(migrationsDir)
    .filter((file) => file.endsWith('.sql'))
    .sort();

  migrationFiles.forEach((file) => {
    const sql = fs.readFileSync(path.join(migrationsDir, file), 'utf-8');
    if (sql.trim().length === 0) return;
    db.exec(sql);
  });
}

function getDb() {
  if (!dbInstance) {
    const db = new sqlite3.Database(DB_PATH);
    dbInstance = db;
    db.serialize(() => {
      db.run('PRAGMA foreign_keys = ON');
      runMigrations(db);
    });
  }
  return dbInstance;
}

module.exports = { getDb, DB_PATH };
