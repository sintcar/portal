const ReleaseService = require('../services/ReleaseService');

async function publish(req, res) {
  const { version, channel, url, checksum, notes, isLatest } = req.body;
  if (!version || !channel || !url) return res.status(400).json({ message: 'version, channel and url are required' });
  try {
    const release = await ReleaseService.publishRelease({ version, channel, url, checksum, notes, isLatest });
    res.status(201).json(release);
  } catch (err) {
    res.status(500).json({ message: 'Unable to publish release', error: err.message });
  }
}

async function list(req, res) {
  try {
    const releases = await ReleaseService.listReleases();
    res.json({ data: releases });
  } catch (err) {
    res.status(500).json({ message: 'Unable to fetch releases', error: err.message });
  }
}

module.exports = { publish, list };
