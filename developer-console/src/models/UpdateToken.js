const { getDb } = require('../config/database');

class UpdateToken {
  static async all() {
    const db = getDb();
    return new Promise((resolve, reject) => {
      db.all('SELECT * FROM update_tokens', (err, rows) => {
        if (err) return reject(err);
        resolve(rows);
      });
    });
  }
}

module.exports = UpdateToken;
