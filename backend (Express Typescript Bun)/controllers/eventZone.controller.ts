import type { Request,Response } from "express";
import { getAllEventZones, getEventZoneById,deleteEventZone,updateEventZone,createEventZone } from "../services/eventZone.service";

export const getEventZones = async (req: Request, res: Response) => {
    try {
        const eventZones = await getAllEventZones();
        res.json(eventZones);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch event zones" });
    }
};

export const getEventZone = async (req: Request, res: Response) => {
    const { id } = req.params;
    try {
        const eventZone = await getEventZoneById(Number(id));
        res.json(eventZone);
    } catch (error) {
        res.status(500).json({ error: "Failed to fetch event zone" });
    }
};

export const createNewEventZone = async (req: Request, res: Response) => {
    const { eventId, name, capacity } = req.body;
    try {
        const eventZone = await createEventZone(eventId, name, capacity);
        res.status(201).json(eventZone);
    } catch (error) {
        res.status(400).json({ error: (error as Error).message || "Failed to create event zone" });
    }
};

export const modifyEventZone = async (req: Request, res: Response) => {
    const { id } = req.params;
    const { eventId, name, capacity } = req.body;
    try {
        const updatedEventZone = await updateEventZone(Number(id), eventId, name, capacity);
        res.json(updatedEventZone);
    } catch (error) {
        res.status(400).json({ error: (error as Error).message || "Failed to update event zone" });
    }
};

export const removeEventZone = async (req: Request, res: Response) => {
    const { id } = req.params;
    try {
        await deleteEventZone(Number(id));
        res.sendStatus(204);
    } catch (error) {
        res.status(500).json({ error: "Failed to delete event zone" });
    }
};