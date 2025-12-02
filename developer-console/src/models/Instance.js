const { getDb } = require('../config/database');

class Instance {
  static async create({ id, name, licenseId, currentVersion, channel, lastSeenAt, createdAt }) {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.run(
        'INSERT INTO instances (id, name, license_id, current_version, channel, last_seen_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
        [id, name, licenseId, currentVersion, channel, lastSeenAt, createdAt, createdAt],
        function callback(err) {
          if (err) return reject(err);
          resolve({ id, name, license_id: licenseId, current_version: currentVersion, channel, last_seen_at: lastSeenAt });
        }
      );
    });
  }

  static async all() {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.all('SELECT * FROM instances ORDER BY created_at DESC', (err, rows) => {
        if (err) return reject(err);
        resolve(rows);
      });
    });
  }
}

module.exports = Instance;
