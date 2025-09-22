import { Router } from "express";
import { loginController } from "../controllers/auth.controller";
import { signOutController } from "../controllers/auth.controller";

const router = Router();

router.post("/login", loginController);
router.post("/sign-out", signOutController);

export default router;