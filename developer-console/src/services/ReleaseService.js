const { v4: uuidv4 } = require('uuid');
const Release = require('../models/Release');

async function publishRelease({ version, channel, url, checksum, notes, isLatest }) {
  const id = uuidv4();
  const releasedAt = new Date().toISOString();
  return Release.create({ id, version, channel, url, checksum, notes, releasedAt, isLatest });
}

async function listReleases() {
  return Release.all();
}

async function latestRelease(channel) {
  return Release.latestByChannel(channel);
}

module.exports = { publishRelease, listReleases, latestRelease };
