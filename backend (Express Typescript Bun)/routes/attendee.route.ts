import { Router } from "express";
import { fetchAllAttendees, fetchAttendeeById, removeAttendee, addAttendee, modifyAttendee, registerAttendee } from "../controllers/attendee.controller";

const router = Router();

router.get("/", fetchAllAttendees);
router.get("/:id", fetchAttendeeById);
router.delete("/:id", removeAttendee);
router.post("/", addAttendee);
router.post("/register", registerAttendee); // Public registration endpoint (no auth required)
router.put("/:id", modifyAttendee);

export default router;