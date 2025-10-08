import { Router } from "express";
import { DashboardController } from "../controllers/SuperAdminDashboard.controller";
import { isAdminOrSuperAdmin } from "../middlewares/auth.middleware";
import { authenticateJWT } from "../middlewares/authenticcateJWT.middleware";

const router = Router();

router.get("/dashboard",
    isAdminOrSuperAdmin, authenticateJWT,
    DashboardController);

export default router;
