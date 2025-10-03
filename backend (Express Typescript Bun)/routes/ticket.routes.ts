import { Router } from "express";
import { getTickets, createNewTicket, getTicket, removeTicket, modifyTicket, getEventTickets } from "../controllers/ticket.controller";
    
const router = Router();

router.get("/", getTickets);
router.post("/", createNewTicket);
router.get("/event/:eventId", getEventTickets); // Public endpoint for getting event tickets
router.get("/:id", getTicket);
router.delete("/:id", removeTicket);
router.put("/:id", modifyTicket);

export default router;
