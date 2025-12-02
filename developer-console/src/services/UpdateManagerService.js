const { v4: uuidv4 } = require('uuid');
const UpdateJob = require('../models/UpdateJob');
const ReleaseService = require('./ReleaseService');

async function createUpdateJob({ instanceId, releaseId, strategy }) {
  const id = uuidv4();
  const now = new Date().toISOString();
  return UpdateJob.create({ id, instanceId, releaseId, status: 'queued', strategy: strategy || 'rolling', startedAt: null, completedAt: null, createdAt: now });
}

async function reportJobStatus({ jobId, status, completedAt }) {
  return UpdateJob.updateStatus(jobId, status, completedAt);
}

async function listJobs() {
  return UpdateJob.all();
}

async function manifest(channel) {
  const latest = await ReleaseService.latestRelease(channel || 'stable');
  return latest ? { channel: latest.channel, version: latest.version, url: latest.url, checksum: latest.checksum, notes: latest.notes } : null;
}

module.exports = { createUpdateJob, reportJobStatus, listJobs, manifest };
