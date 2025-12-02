const { getDb } = require('../config/database');

class License {
  static async create({ id, licenseKey, customer, expiresAt, status, notes, issuedBy, createdAt }) {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.run(
        'INSERT INTO licenses (id, license_key, customer, expires_at, status, notes, issued_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [id, licenseKey, customer, expiresAt, status, notes, issuedBy, createdAt, createdAt],
        function callback(err) {
          if (err) return reject(err);
          resolve({ id, license_key: licenseKey, customer, expires_at: expiresAt, status, notes, issued_by: issuedBy });
        }
      );
    });
  }

  static async all() {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.all('SELECT * FROM licenses ORDER BY created_at DESC', (err, rows) => {
        if (err) return reject(err);
        resolve(rows);
      });
    });
  }
}

module.exports = License;
