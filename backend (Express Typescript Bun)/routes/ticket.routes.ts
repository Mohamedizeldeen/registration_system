import { Router } from "express";
import { getTickets, createNewTicket, getTicket, removeTicket, modifyTicket } from "../controllers/ticket.controller";
    
const router = Router();

router.get("/", getTickets);
router.post("/", createNewTicket);
router.get("/:id", getTicket);
router.delete("/:id", removeTicket);
router.put("/:id", modifyTicket);

export default router;
