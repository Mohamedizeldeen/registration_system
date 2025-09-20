import { Router } from "express";
import { DashboardController } from "../controllers/SuperAdminDashboard.controller";

const router = Router();

router.get("/dashboard", DashboardController);

export default router;
