import { Router } from "express";
import { fetchAllAttendees, fetchAttendeeById, removeAttendee, addAttendee, modifyAttendee } from "../controllers/attendee.controller";

const router = Router();

router.get("/", fetchAllAttendees);
router.get("/:id", fetchAttendeeById);
router.delete("/:id", removeAttendee);
router.post("/", addAttendee);
router.put("/:id", modifyAttendee);

export default router;