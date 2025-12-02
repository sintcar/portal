const { getDb } = require('../config/database');

class User {
  static async findByEmail(email) {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.get('SELECT * FROM dev_admins WHERE email = ?', [email], (err, row) => {
        if (err) return reject(err);
        resolve(row);
      });
    });
  }

  static async findById(id) {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.get('SELECT * FROM dev_admins WHERE id = ?', [id], (err, row) => {
        if (err) return reject(err);
        resolve(row);
      });
    });
  }

  static async create({ id, email, name, passwordHash, createdAt }) {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.run(
        'INSERT INTO dev_admins (id, email, name, password_hash, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)',
        [id, email, name, passwordHash, createdAt, createdAt],
        function callback(err) {
          if (err) return reject(err);
          resolve({ id, email, name, password_hash: passwordHash, created_at: createdAt, updated_at: createdAt });
        }
      );
    });
  }
}

module.exports = User;
