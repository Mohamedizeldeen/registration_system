import { Router } from "express";
import { createNewEventZone, getEventZone, getEventZones, removeEventZone, modifyEventZone } from "../controllers/eventZone.controller";

const router = Router();

router.get("/", getEventZones);
router.post("/", createNewEventZone);
router.get("/:id", getEventZone);
router.delete("/:id", removeEventZone);
router.put("/:id", modifyEventZone);
export default router;