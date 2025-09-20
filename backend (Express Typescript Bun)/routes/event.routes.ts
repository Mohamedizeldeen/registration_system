import { Router } from "express";
import { createNewEvent, modifyEvent, removeEvent, getEvents, getEvent } from "../controllers/event.Controller";

const router = Router();

router.get("/", getEvents);
router.post("/", createNewEvent);
router.get("/:id", getEvent);
router.put("/:id", modifyEvent);
router.delete("/:id", removeEvent);

export default router;
