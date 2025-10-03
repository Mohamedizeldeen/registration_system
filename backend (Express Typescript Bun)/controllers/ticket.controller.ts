import type { Request, Response } from "express";
import { getAllTickets,getTicketById, createTicket, deleteTicket,updateTicket, getTicketsByEventId } from "../services/ticket.service";

export const getTickets = async (req: Request, res: Response) => {
    try {
        const tickets = await getAllTickets();
        res.json(tickets);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch tickets" });
    }
};

export const createNewTicket = async (req: Request, res: Response) => {
    const { eventId, eventZoneId, couponId, name, info, price, quantity } = req.body;
    try {
        const newTicket = await createTicket(eventId, eventZoneId, couponId, name, info, price, quantity);
        res.status(201).json(newTicket);
    } catch (error) {
        res.status(500).json({ error: "Failed to create ticket" });
    }
};
export const getTicket = async (req: Request, res: Response) => {
    const { id } = req.params;
    try {
        const ticket = await getTicketById(Number(id));
        res.json(ticket);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch ticket" });
    }
};
export const removeTicket = async (req: Request, res: Response) => {
    const { id } = req.params;
    try {
        await deleteTicket(Number(id));
        res.sendStatus(204);
    } catch (error) {
        res.status(500).json({ error: "Failed to delete ticket" });
    }
};
export const modifyTicket = async (req: Request, res: Response) => {
    const { id } = req.params;
    const { eventId, eventZoneId, couponId, name, info, price, quantity } = req.body;
    try {
        const updatedTicket = await updateTicket(Number(id), eventId, eventZoneId, couponId, name, info, price, quantity);
        res.json(updatedTicket);
    } catch (error) {
        res.status(500).json({ error: "Failed to update ticket" });
    }
};

// Get tickets by event ID (public endpoint for sharing)
export const getEventTickets = async (req: Request, res: Response) => {
    const { eventId } = req.params;
    if (!eventId) {
        return res.status(400).json({ error: "Event ID is required" });
    }
    
    const numericEventId = Number(eventId);
    if (isNaN(numericEventId)) {
        return res.status(400).json({ error: "Event ID must be a valid number" });
    }
    
    try {
        const tickets = await getTicketsByEventId(numericEventId);
        res.json(tickets);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch tickets" });
    }
};