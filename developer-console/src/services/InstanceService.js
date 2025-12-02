const { v4: uuidv4 } = require('uuid');
const Instance = require('../models/Instance');

async function registerInstance({ name, licenseId, currentVersion, channel, lastSeenAt }) {
  const id = uuidv4();
  const now = new Date().toISOString();
  return Instance.create({ id, name, licenseId, currentVersion, channel, lastSeenAt, createdAt: now });
}

async function listInstances() {
  return Instance.all();
}

module.exports = { registerInstance, listInstances };
