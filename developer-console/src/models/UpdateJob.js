const { getDb } = require('../config/database');

class UpdateJob {
  static async create({ id, instanceId, releaseId, status, strategy, startedAt, completedAt, createdAt }) {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.run(
        'INSERT INTO update_jobs (id, instance_id, release_id, status, strategy, started_at, completed_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [id, instanceId, releaseId, status, strategy, startedAt, completedAt, createdAt, createdAt],
        function callback(err) {
          if (err) return reject(err);
          resolve({ id, instance_id: instanceId, release_id: releaseId, status, strategy, started_at: startedAt, completed_at: completedAt });
        }
      );
    });
  }

  static async updateStatus(id, status, completedAt) {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.run(
        'UPDATE update_jobs SET status = ?, completed_at = COALESCE(?, completed_at), updated_at = ? WHERE id = ?',
        [status, completedAt, new Date().toISOString(), id],
        function callback(err) {
          if (err) return reject(err);
          resolve(this.changes > 0);
        }
      );
    });
  }

  static async all() {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.all('SELECT * FROM update_jobs ORDER BY created_at DESC', (err, rows) => {
        if (err) return reject(err);
        resolve(rows);
      });
    });
  }
}

module.exports = UpdateJob;
