const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const { v4: uuidv4 } = require('uuid');
const User = require('../models/User');

const JWT_SECRET = process.env.DEVCONSOLE_JWT_SECRET || 'devconsole-secret';

async function ensureSeedAdmin() {
  const existing = await User.findByEmail('devadmin@example.com');
  if (existing) return existing;
  const passwordHash = await bcrypt.hash('devadmin', 10);
  const now = new Date().toISOString();
  return User.create({ id: uuidv4(), email: 'devadmin@example.com', name: 'Dev Admin', passwordHash, createdAt: now });
}

async function login(email, password) {
  const user = await User.findByEmail(email);
  if (!user) return null;
  const ok = await bcrypt.compare(password, user.password_hash);
  if (!ok) return null;
  const token = jwt.sign({ sub: user.id, email: user.email }, JWT_SECRET, { expiresIn: '12h' });
  return { token, user };
}

module.exports = { ensureSeedAdmin, login, JWT_SECRET };
