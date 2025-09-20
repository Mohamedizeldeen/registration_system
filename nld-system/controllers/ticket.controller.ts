import type { Request, Response } from "express";
import { getAllTickets,getTicketById, createTicket, deleteTicket,updateTicket } from "../services/ticket.service";

export const getTickets = async (req: Request, res: Response) => {
    const tickets = await getAllTickets();
    res.json(tickets);
}
export const createNewTicket = async (req: Request, res: Response) => {
    const { eventId, eventZoneId, couponId, name, info, price, quantity } = req.body;
    const newTicket = await createTicket(eventId, eventZoneId, couponId, name, info, price, quantity);
    res.status(201).json(newTicket);
};
export const getTicket = async (req: Request, res: Response) => {
    const { id } = req.params;
    const ticket = await getTicketById(Number(id));
    res.json(ticket);
};
export const removeTicket = async (req: Request, res: Response) => {
    const { id } = req.params;
    await deleteTicket(Number(id));
    res.sendStatus(204);
};
export const modifyTicket = async (req: Request, res: Response) => {
    const { id } = req.params;
    const { eventId, eventZoneId, couponId, name, info, price, quantity } = req.body;
    const updatedTicket = await updateTicket(Number(id), eventId, eventZoneId, couponId, name, info, price, quantity);
    res.json(updatedTicket);
}