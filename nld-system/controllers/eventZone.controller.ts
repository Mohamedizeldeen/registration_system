import type { Request,Response } from "express";
import { getAllEventZones, getEventZoneById,deleteEventZone,updateEventZone,createEventZone } from "../services/eventZone.service";

export const getEventZones = async (req: Request, res: Response) => {
    const eventZones = await getAllEventZones();
    res.json(eventZones);
};

export const getEventZone = async (req: Request, res: Response) => {
    const { id } = req.params;
    const eventZone = await getEventZoneById(Number(id));
    res.json(eventZone);
};

export const createNewEventZone = async (req: Request, res: Response) => {
    const { eventId, name, capacity } = req.body;
    const eventZone = await createEventZone(eventId, name, capacity);
    res.status(201).json(eventZone);
};

export const modifyEventZone = async (req: Request, res: Response) => {
    const { id } = req.params;
    const { eventId, name, capacity } = req.body;
    const updatedEventZone = await updateEventZone(Number(id), eventId, name, capacity);
    res.json(updatedEventZone);
};

export const removeEventZone = async (req: Request, res: Response) => {
    const { id } = req.params;
    await deleteEventZone(Number(id));
    res.sendStatus(204);
};