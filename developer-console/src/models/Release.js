const { getDb } = require('../config/database');

class Release {
  static async create({ id, version, channel, url, checksum, notes, releasedAt, isLatest }) {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.run(
        'INSERT INTO releases (id, version, channel, url, checksum, notes, released_at, is_latest) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
        [id, version, channel, url, checksum, notes, releasedAt, isLatest ? 1 : 0],
        function callback(err) {
          if (err) return reject(err);
          resolve({ id, version, channel, url, checksum, notes, released_at: releasedAt, is_latest: isLatest ? 1 : 0 });
        }
      );
    });
  }

  static async all() {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.all('SELECT * FROM releases ORDER BY released_at DESC', (err, rows) => {
        if (err) return reject(err);
        resolve(rows);
      });
    });
  }

  static async latestByChannel(channel) {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.get(
        'SELECT * FROM releases WHERE channel = ? ORDER BY released_at DESC LIMIT 1',
        [channel],
        (err, row) => {
          if (err) return reject(err);
          resolve(row);
        }
      );
    });
  }
}

module.exports = Release;
