const jwt = require('jsonwebtoken');
const { JWT_SECRET } = require('../services/AuthService');

function authMiddleware(req, res, next) {
  const header = req.headers.authorization;
  if (!header || !header.startsWith('Bearer ')) {
    return res.status(401).json({ message: 'Unauthorized' });
  }
  const token = header.replace('Bearer ', '');
  try {
    const payload = jwt.verify(token, JWT_SECRET);
    req.user = { id: payload.sub, email: payload.email };
    next();
  } catch (err) {
    res.status(401).json({ message: 'Invalid token' });
  }
}

module.exports = authMiddleware;
