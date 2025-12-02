const { Router } = require('express');
const AuthController = require('../controllers/AuthController');
const LicenseController = require('../controllers/LicenseController');
const InstanceController = require('../controllers/InstanceController');
const ReleaseController = require('../controllers/ReleaseController');
const UpdateManagerController = require('../controllers/UpdateManagerController');
const authMiddleware = require('../middleware/auth');

const router = Router();

router.post('/auth/login', AuthController.login);

router.use(authMiddleware);

// License issuance
router.post('/licenses', LicenseController.issue);
router.get('/licenses', LicenseController.list);

// Instance monitoring
router.post('/instances', InstanceController.register);
router.get('/instances', InstanceController.list);
router.post('/instances/:id/update', InstanceController.triggerUpdate);

// Releases
router.post('/releases', ReleaseController.publish);
router.get('/releases', ReleaseController.list);

// Update manager integration
router.get('/update-manager/manifest', UpdateManagerController.manifest);
router.post('/update-manager/jobs/report', UpdateManagerController.report);
router.get('/update-manager/jobs', UpdateManagerController.list);

module.exports = router;
